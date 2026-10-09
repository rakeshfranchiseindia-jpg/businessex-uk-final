<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;
use Tests\TestCase;

class LegacyProfileMigrationTest extends TestCase
{
    private const LEGACY_TYPES = [
        1 => ['profile_business', 'business_id', 'business_profile_str'],
        2 => ['profile_investor', 'investor_id', 'inv_profile_str'],
        3 => ['profile_lenders', 'lender_id', 'lender_profile_str'],
        4 => ['profile_mentors', 'mentor_id', 'mentor_profile_str'],
        5 => ['profile_incubators', 'incubator_id', 'incubator_profile_str'],
        6 => ['profile_broker', 'broker_id', 'broker_profile_str'],
        7 => ['profile_startups', 'startup_id', 'startup_profile_str'],
    ];

    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('profiles');
        Schema::dropIfExists('user_profiles');
        Schema::dropIfExists('user_account');

        foreach (self::LEGACY_TYPES as [$tableName]) {
            Schema::dropIfExists($tableName);
        }

        Schema::create('user_account', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->primary();
        });

        Schema::create('user_profiles', function (Blueprint $table): void {
            $table->increments('user_prof_id');
            $table->unsignedInteger('user_id');
            $table->integer('profile_id');
            $table->tinyInteger('profile_type');
            $table->string('profile_str', 20);
            $table->tinyInteger('profile_status');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });

        foreach (self::LEGACY_TYPES as [$tableName, $idColumn, $keyColumn]) {
            Schema::create($tableName, function (Blueprint $table) use ($idColumn, $keyColumn): void {
                $table->integer($idColumn)->primary();
                $table->string($keyColumn, 20);
                $table->unsignedInteger('user_id');
            });
        }
    }

    public function test_it_imports_owned_profile_types_and_preserves_multiple_profiles_per_user(): void
    {
        DB::table('user_account')->insert(['user_id' => 35]);

        $profiles = [
            [1, 14, 'business-14'],
            [1, 15, 'business-15'],
            [2, 14, 'investor-14'],
            [3, 4, 'lender-4'],
            [4, 14, 'mentor-14'],
            [5, 2, 'incubator-2'],
            [6, 2, 'broker-2'],
            [7, 14, 'startup-14'],
        ];

        foreach ($profiles as $index => [$type, $profileId, $key]) {
            [$tableName, $idColumn, $keyColumn] = self::LEGACY_TYPES[$type];
            DB::table($tableName)->insert([
                $idColumn => $profileId,
                $keyColumn => $key,
                'user_id' => 35,
            ]);
            DB::table('user_profiles')->insert([
                'user_id' => 35,
                'profile_id' => $profileId,
                'profile_type' => $type,
                'profile_str' => $key,
                'profile_status' => $index % 3,
                'created_at' => '2026-10-01 10:00:00',
                'updated_at' => '2026-10-02 10:00:00',
            ]);
        }

        $migration = require database_path('migrations/legacy/2026_10_05_000000_create_profiles_and_import_legacy_profile_links.php');
        $migration->up();

        $this->assertSame(8, DB::table('profiles')->count());
        $this->assertSame(2, DB::table('profiles')->where('user_id', 35)->where('profile_type', 'business')->count());
        $this->assertSame('startup', DB::table('profiles')->where('legacy_user_profile_id', 8)->value('profile_type'));
        $this->assertSame('startup-14', DB::table('profiles')->where('legacy_user_profile_id', 8)->value('legacy_profile_key'));
        $this->assertSame(7, DB::table('profiles')->where('legacy_user_profile_id', 8)->value('legacy_profile_type'));
        $this->assertSame(0, DB::table('profiles')->where('legacy_user_profile_id', 1)->value('status'));

        $migration->down();

        $this->assertFalse(Schema::hasTable('profiles'));
        $this->assertSame(8, DB::table('user_profiles')->count());
    }

    public function test_it_refuses_to_migrate_a_mapping_that_does_not_match_its_legacy_profile(): void
    {
        DB::table('user_account')->insert(['user_id' => 17]);
        DB::table('profile_business')->insert([
            'business_id' => 14,
            'business_profile_str' => 'actual-profile-key',
            'user_id' => 17,
        ]);
        DB::table('user_profiles')->insert([
            'user_id' => 17,
            'profile_id' => 14,
            'profile_type' => 1,
            'profile_str' => 'wrong-profile-key',
            'profile_status' => 1,
        ]);

        $migration = require database_path('migrations/legacy/2026_10_05_000000_create_profiles_and_import_legacy_profile_links.php');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('does not match its legacy profile record and owner');
        $migration->up();
    }
}
