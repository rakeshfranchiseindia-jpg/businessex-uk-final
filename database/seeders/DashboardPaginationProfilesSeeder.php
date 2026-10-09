<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class DashboardPaginationProfilesSeeder extends Seeder
{
    private const OWNER_EMAIL = 'demo.business05@example.test';

    private const PROFILES = [
        ['company' => 'Pagination Test Business 01 Ltd', 'city' => 'London'],
        ['company' => 'Pagination Test Business 02 Ltd', 'city' => 'Edinburgh'],
        ['company' => 'Pagination Test Business 03 Ltd', 'city' => 'Manchester'],
        ['company' => 'Pagination Test Business 04 Ltd', 'city' => 'Bristol'],
        ['company' => 'Pagination Test Business 05 Ltd', 'city' => 'Leeds'],
        ['company' => 'Pagination Test Business 06 Ltd', 'city' => 'Glasgow'],
        ['company' => 'Pagination Test Business 07 Ltd', 'city' => 'Cardiff'],
        ['company' => 'Pagination Test Business 08 Ltd', 'city' => 'York'],
        ['company' => 'Pagination Test Business 09 Ltd', 'city' => 'Newcastle'],
        ['company' => 'Pagination Test Business 10 Ltd', 'city' => 'Liverpool'],
        ['company' => 'Pagination Test Business 11 Ltd', 'city' => 'Oxford'],
        ['company' => 'Pagination Test Business 12 Ltd', 'city' => 'Cambridge'],
    ];

    public function run(): void
    {
        foreach (['user_account', 'user_profiles', 'profile_business'] as $table) {
            if (! Schema::hasTable($table)) {
                throw new RuntimeException("Dashboard pagination seed requires table [{$table}].");
            }
        }

        $owner = DB::table('user_account')->where('email', self::OWNER_EMAIL)->first();
        if (! $owner) {
            throw new RuntimeException('Dashboard pagination seed requires the demo.business05@example.test account.');
        }

        $columns = Schema::getColumnListing('profile_business');
        $created = 0;

        DB::transaction(function () use ($owner, $columns, &$created): void {
            foreach (self::PROFILES as $index => $record) {
                $profileKey = 'TPBUS'.str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
                $profile = DB::table('profile_business')
                    ->where('business_profile_str', $profileKey)
                    ->first();

                if ($profile && (int) $profile->user_id !== (int) $owner->user_id) {
                    throw new RuntimeException("Dashboard pagination profile key [{$profileKey}] belongs to another account.");
                }

                $now = now();
                if (! $profile) {
                    $values = [
                        'business_profile_str' => $profileKey,
                        'user_id' => $owner->user_id,
                        'seller_name' => $owner->name,
                        'seller_designation' => 'Director',
                        'seller_mobile' => '7700900100',
                        'seller_email' => $owner->email,
                        'advmt_headline' => $record['company'].' growth opportunity',
                        'seller_intro' => 'A UK-based organisation supporting businesses with long-term growth opportunities.',
                        'seller_company' => $record['company'],
                        'estb_year' => 2018,
                        'emp_count' => '10-50',
                        'entity_type' => 'Private Limited Company',
                        'business_type' => 'B2B',
                        'industry_sector' => 'Retail',
                        'company_summary' => 'Pagination demo profile for dashboard testing.',
                        'ofc_city' => $record['city'],
                        'ofc_country' => 'United Kingdom',
                        'business_profile_status' => 1,
                        'membership_paid' => 0,
                        'membership_plan' => 0,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];

                    $profileId = DB::table('profile_business')->insertGetId(
                        array_intersect_key($values, array_flip($columns)),
                        'business_id'
                    );
                    $profile = (object) ['business_id' => $profileId];
                    $created++;
                }

                DB::table('user_profiles')->updateOrInsert(
                    ['profile_type' => 1, 'profile_str' => $profileKey],
                    [
                        'user_id' => $owner->user_id,
                        'profile_id' => $profile->business_id,
                        'profile_status' => 1,
                        'updated_at' => $now,
                        'created_at' => $now,
                    ]
                );

                if (Schema::hasTable('profiles')
                    && Schema::hasColumns('profiles', [
                        'legacy_profile_type',
                        'legacy_profile_id',
                        'user_id',
                        'profile_type',
                        'legacy_profile_key',
                        'legacy_user_profile_id',
                        'status',
                        'created_at',
                        'updated_at',
                    ])) {
                    $userProfileId = DB::table('user_profiles')
                        ->where('profile_type', 1)
                        ->where('profile_str', $profileKey)
                        ->value('user_prof_id');

                    DB::table('profiles')->updateOrInsert(
                        ['legacy_profile_type' => 1, 'legacy_profile_id' => $profile->business_id],
                        [
                            'user_id' => $owner->user_id,
                            'profile_type' => 'business',
                            'legacy_profile_key' => $profileKey,
                            'legacy_user_profile_id' => $userProfileId,
                            'status' => 1,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]
                    );
                }
            }
        });

        $this->command?->info('Ensured 12 pagination test business profiles for '.self::OWNER_EMAIL." ({$created} created).");
    }
}
