<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // This migration runs only against the imported BusinessX legacy database.
    private const PROFILE_TYPES = [
        1 => ['business', 'profile_business', 'business_id', 'business_profile_str'],
        2 => ['investor', 'profile_investor', 'investor_id', 'inv_profile_str'],
        3 => ['lender', 'profile_lenders', 'lender_id', 'lender_profile_str'],
        4 => ['mentor', 'profile_mentors', 'mentor_id', 'mentor_profile_str'],
        5 => ['incubator', 'profile_incubators', 'incubator_id', 'incubator_profile_str'],
        6 => ['broker', 'profile_broker', 'broker_id', 'broker_profile_str'],
        7 => ['startup', 'profile_startups', 'startup_id', 'startup_profile_str'],
    ];

    public function up(): void
    {
        $this->validateLegacySchema();

        if (Schema::hasTable('profiles')) {
            throw new \RuntimeException('The profiles table already exists; refusing to import legacy profile links into an unknown state.');
        }

        Schema::create('profiles', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('profile_type', 32);
            $table->unsignedTinyInteger('legacy_profile_type');
            $table->unsignedInteger('legacy_profile_id');
            $table->string('legacy_profile_key', 191);
            $table->unsignedInteger('legacy_user_profile_id')->unique();
            $table->smallInteger('status')->default(0);
            $table->timestamps();

            $table->unique(
                ['legacy_profile_type', 'legacy_profile_id'],
                'profiles_legacy_type_profile_unique'
            );
            $table->index(['user_id', 'profile_type'], 'profiles_user_type_index');
            $table->foreign('user_id', 'profiles_user_account_fk')
                ->references('user_id')
                ->on('user_account')
                ->cascadeOnDelete();
        });

        foreach (self::PROFILE_TYPES as $legacyType => [$profileType, $tableName, $idColumn, $keyColumn]) {
            DB::table('user_profiles as mapping')
                ->join($tableName . ' as legacy', 'legacy.' . $idColumn, '=', 'mapping.profile_id')
                ->where('mapping.profile_type', $legacyType)
                ->whereColumn('legacy.user_id', 'mapping.user_id')
                ->whereColumn('legacy.' . $keyColumn, 'mapping.profile_str')
                ->select([
                    'mapping.user_id as user_id',
                    DB::raw("'" . $profileType . "' as profile_type"),
                    DB::raw((string) $legacyType . ' as legacy_profile_type'),
                    'mapping.profile_id as legacy_profile_id',
                    'mapping.profile_str as legacy_profile_key',
                    'mapping.user_prof_id as legacy_user_profile_id',
                    'mapping.profile_status as status',
                    'mapping.created_at as created_at',
                    'mapping.updated_at as updated_at',
                    'mapping.user_prof_id as migration_chunk_id',
                ])
                ->orderBy('mapping.user_prof_id')
                ->chunkById(500, function ($rows): void {
                    $records = $rows->map(static function ($row): array {
                        $record = (array) $row;
                        unset($record['migration_chunk_id']);

                        return $record;
                    })->all();

                    DB::table('profiles')->insert($records);
                }, 'mapping.user_prof_id', 'migration_chunk_id');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }

    private function validateLegacySchema(): void
    {
        $requiredTables = ['user_account', 'user_profiles'];
        foreach (self::PROFILE_TYPES as [, $tableName]) {
            $requiredTables[] = $tableName;
        }

        foreach ($requiredTables as $tableName) {
            if (!Schema::hasTable($tableName)) {
                throw new \RuntimeException("Required legacy table [{$tableName}] is missing; import the BusinessX database dump before running this migration.");
            }
        }

        $supportedTypes = array_keys(self::PROFILE_TYPES);
        $unknownType = DB::table('user_profiles')
            ->whereNotIn('profile_type', $supportedTypes)
            ->value('profile_type');

        if ($unknownType !== null) {
            throw new \RuntimeException("Unsupported legacy profile_type [{$unknownType}] found in user_profiles; refusing to guess its profile table.");
        }

        $orphanedAccountLink = DB::table('user_profiles as mapping')
            ->leftJoin('user_account as account', 'account.user_id', '=', 'mapping.user_id')
            ->whereNull('account.user_id')
            ->exists();

        if ($orphanedAccountLink) {
            throw new \RuntimeException('At least one user_profiles row references a missing user_account; repair the ownership mapping before migrating.');
        }

        foreach (self::PROFILE_TYPES as $legacyType => [, $tableName, $idColumn, $keyColumn]) {
            $orphanedProfileLink = DB::table('user_profiles as mapping')
                ->leftJoin($tableName . ' as legacy', function ($join) use ($idColumn, $keyColumn): void {
                    $join->on('legacy.' . $idColumn, '=', 'mapping.profile_id')
                        ->on('legacy.user_id', '=', 'mapping.user_id')
                        ->on('legacy.' . $keyColumn, '=', 'mapping.profile_str');
                })
                ->where('mapping.profile_type', $legacyType)
                ->whereNull('legacy.' . $idColumn)
                ->exists();

            if ($orphanedProfileLink) {
                throw new \RuntimeException("A user_profiles row with profile_type [{$legacyType}] does not match its legacy profile record and owner; repair it before migrating.");
            }
        }

        $duplicateProfileLink = DB::table('user_profiles')
            ->select('profile_type', 'profile_id')
            ->groupBy('profile_type', 'profile_id')
            ->havingRaw('COUNT(*) > 1')
            ->exists();

        if ($duplicateProfileLink) {
            throw new \RuntimeException('Duplicate user_profiles profile mappings exist; deduplicate them before migrating.');
        }
    }
};
