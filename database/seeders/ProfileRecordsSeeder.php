<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class ProfileRecordsSeeder extends Seeder
{
    private const DEMO_PASSWORD = 'P@ssw0rd@123';

    private const PROFILES = [
        'business' => [
            'table' => 'profile_business',
            'id' => 'business_id',
            'key' => 'business_profile_str',
            'email' => 'seller_email',
            'type' => 1,
            'code' => 'BSN',
            'profiles' => [
                ['person' => 'Oliver Bennett', 'company' => 'Northstar Foods Ltd', 'industry' => 'Food & beverage', 'city' => 'London'],
                ['person' => 'Amelia Clarke', 'company' => 'Greenfield Renewables Ltd', 'industry' => 'Energy & Environment', 'city' => 'Bristol'],
                ['person' => 'George Harris', 'company' => 'Oak & Stone Interiors Ltd', 'industry' => 'Building construction & Home products', 'city' => 'Manchester'],
                ['person' => 'Isla Morgan', 'company' => 'BrightPath Learning Ltd', 'industry' => 'Education', 'city' => 'Leeds'],
                ['person' => 'Freddie Patel', 'company' => 'Harbour Retail Group Ltd', 'industry' => 'Retail', 'city' => 'Edinburgh'],
            ],
        ],
        'mentor' => [
            'table' => 'profile_mentors',
            'id' => 'mentor_id',
            'key' => 'mentor_profile_str',
            'email' => 'mentor_email',
            'type' => 4,
            'code' => 'MNT',
            'profiles' => [
                ['name' => 'Charlotte Reed', 'company' => 'Reed Growth Advisory', 'city' => 'London', 'expertise' => 'Business strategy and scaling'],
                ['name' => 'Noah Williams', 'company' => 'Williams Finance Partners', 'city' => 'Manchester', 'expertise' => 'Finance and fundraising'],
                ['name' => 'Grace Thompson', 'company' => 'Thompson Digital Studio', 'city' => 'Bristol', 'expertise' => 'Digital marketing and ecommerce'],
                ['name' => 'Arthur Lewis', 'company' => 'Lewis Operations Consulting', 'city' => 'Leeds', 'expertise' => 'Operations and supply chains'],
                ['name' => 'Mia Campbell', 'company' => 'Campbell People Advisory', 'city' => 'Edinburgh', 'expertise' => 'Leadership and people development'],
            ],
        ],
        'investor' => [
            'table' => 'profile_investor',
            'id' => 'investor_id',
            'key' => 'inv_profile_str',
            'email' => 'inv_email',
            'type' => 2,
            'code' => 'INV',
            'profiles' => [
                ['name' => 'Ethan Walker', 'company' => 'Cedar Ventures', 'city' => 'London', 'focus' => 'Technology and SaaS'],
                ['name' => 'Evie Robinson', 'company' => 'Riverstone Capital', 'city' => 'Manchester', 'focus' => 'Consumer and retail'],
                ['name' => 'Jack Wright', 'company' => 'Meridian Impact Fund', 'city' => 'Bristol', 'focus' => 'Clean energy and climate'],
                ['name' => 'Poppy Young', 'company' => 'Northbridge Investment Group', 'city' => 'Leeds', 'focus' => 'Healthcare and education'],
                ['name' => 'Leo Evans', 'company' => 'Forth Business Angels', 'city' => 'Edinburgh', 'focus' => 'Manufacturing and logistics'],
            ],
        ],
        'startup' => [
            'table' => 'profile_startups',
            'id' => 'startup_id',
            'key' => 'startup_profile_str',
            'email' => 'startup_email',
            'type' => 7,
            'code' => 'STP',
            'profiles' => [
                ['founder' => 'Lily Scott', 'company' => 'LoopCart', 'city' => 'London', 'pitch' => 'Reusable packaging and returns for online retailers.'],
                ['founder' => 'Oscar Green', 'company' => 'Gridwise Energy', 'city' => 'Manchester', 'pitch' => 'Smart energy management for small commercial buildings.'],
                ['founder' => 'Freya Hall', 'company' => 'CareConnect', 'city' => 'Bristol', 'pitch' => 'Simple digital coordination for community care teams.'],
                ['founder' => 'Harry Adams', 'company' => 'FarmRoute', 'city' => 'Leeds', 'pitch' => 'Direct-to-market logistics for independent food producers.'],
                ['founder' => 'Ella Baker', 'company' => 'SkillSpring', 'city' => 'Edinburgh', 'pitch' => 'Flexible skills training for growing small businesses.'],
            ],
        ],
    ];

    public function run(): void
    {
        $this->ensureTablesExist();
        $now = now();
        $demoCount = 0;

        foreach (self::PROFILES as $profileType => $definition) {
            foreach ($definition['profiles'] as $index => $record) {
                $sequence = $index + 1;
                $profileKey = 'DEMO' . $definition['code'] . str_pad((string) $sequence, 2, '0', STR_PAD_LEFT);
                $email = 'demo.' . $profileType . str_pad((string) $sequence, 2, '0', STR_PAD_LEFT) . '@example.test';
                DB::transaction(function () use ($definition, $email, $now, $profileKey, $profileType, $record, $sequence): void {
                    $userId = $this->ensureUser($email, $record, $profileType, $definition['code'], $sequence, $now);
                    $profileValues = $this->profileValues(
                        $profileType,
                        $record,
                        $sequence - 1,
                        $email,
                        $userId,
                        $profileKey,
                        $now
                    );

                    DB::table($definition['table'])->updateOrInsert(
                        [$definition['key'] => $profileKey],
                        $profileValues
                    );

                    $profileId = DB::table($definition['table'])
                        ->where($definition['key'], $profileKey)
                        ->value($definition['id']);

                    if (!$profileId) {
                        throw new RuntimeException("Unable to retrieve the seeded profile [{$profileKey}].");
                    }

                    $userProfileId = $this->ensureUserProfile(
                        $userId,
                        (int) $profileId,
                        (int) $definition['type'],
                        $profileKey,
                        $now
                    );

                    $this->ensureCanonicalProfile(
                        $profileType,
                        (int) $definition['type'],
                        (int) $profileId,
                        $userId,
                        $userProfileId,
                        $profileKey,
                        $now
                    );
                });

                $demoCount++;
            }
        }

        $this->command?->info("ProfileRecordsSeeder: ensured {$demoCount} demo profiles across business, mentor, investor, and startup.");
    }

    private function ensureTablesExist(): void
    {
        $tables = ['user_account', 'user_profiles'];
        foreach (self::PROFILES as $definition) {
            $tables[] = $definition['table'];
        }

        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) {
                throw new RuntimeException("ProfileRecordsSeeder requires table [{$table}]. Run the migrations/import the BusinessX schema first.");
            }
        }
    }

    private function ensureUser(
        string $email,
        array $record,
        string $profileType,
        string $code,
        int $sequence,
        mixed $now
    ): int {
        $user = DB::table('user_account')->where('email', $email)->first();
        if ($user) {
            return (int) $user->user_id;
        }

        $personName = $record['person'] ?? $record['name'] ?? $record['founder'];
        $company = $record['company'];

        $values = [
            'user_rand_id' => 'DEMOU' . $code . str_pad((string) $sequence, 2, '0', STR_PAD_LEFT),
            'name' => $personName,
            'email' => $email,
            'password' => Hash::make(self::DEMO_PASSWORD),
            'mobile' => '+44 7700 ' . str_pad((string) (900000 + $sequence), 6, '0', STR_PAD_LEFT),
            'company_name' => $company,
            'is_active' => 1,
            'reg_source' => 1,
            'reg_profile' => $profileType,
            'last_notify_at' => $now,
            'last_login_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ];
        if (Schema::hasColumn('user_account', 'email_verified_at')) {
            $values['email_verified_at'] = $now;
        }

        return (int) DB::table('user_account')->insertGetId($values, 'user_id');
    }

    private function profileValues(
        string $profileType,
        array $record,
        int $index,
        string $email,
        int $userId,
        string $profileKey,
        mixed $now
    ): array {
        $common = [
            'user_id' => $userId,
            'business_profile_str' => $profileKey,
            'startup_profile_str' => $profileKey,
            'mentor_profile_str' => $profileKey,
            'inv_profile_str' => $profileKey,
            'seller_name' => $record['person'] ?? null,
            'seller_email' => $email,
            'seller_mobile' => '07700900001',
            'seller_designation' => 'Director',
            'seller_company' => $record['company'] ?? null,
            'startup_name' => $record['founder'] ?? null,
            'startup_email' => $email,
            'startup_mobile' => '07700900002',
            'startup_designation' => 'Founder',
            'name_of_entity' => $record['company'] ?? null,
            'mentor_name' => $record['name'] ?? null,
            'mentor_email' => $email,
            'mentor_mobile' => '07700900003',
            'mentor_company' => $record['company'] ?? null,
            'mentor_country' => 'United Kingdom',
            'mentor_city' => $record['city'] ?? null,
            'mentor_adv_headline' => ($record['expertise'] ?? 'Experienced business mentor') . ' mentor',
            'mentor_intro' => 'Experienced adviser helping growing UK businesses build practical plans and make confident decisions.',
            'mentor_profile_summary' => $record['expertise'] ?? null,
            'mentor_occupation' => 1,
            'mentor_designation' => 'Business Mentor',
            'mentor_profile_status' => 1,
            'inv_name' => $record['name'] ?? null,
            'inv_email' => $email,
            'inv_mobile' => '07700900004',
            'inv_country' => 'United Kingdom',
            'inv_city' => $record['city'] ?? null,
            'inv_headline' => 'Investing in ' . ($record['focus'] ?? 'UK businesses'),
            'inv_intro' => 'Long-term investor supporting ambitious UK businesses with capital and practical experience.',
            'inv_type' => 1,
            'inv_abt_urself' => 'Focused on sustainable growth, clear governance and strong founding teams.',
            'company_name' => $record['company'] ?? null,
            'company_designation' => 'Investment Director',
            'company_country' => 'United Kingdom',
            'company_city' => $record['city'] ?? null,
            'company_website' => 'https://example.test',
            'company_summary' => 'A UK-based organisation supporting businesses with long-term growth opportunities.',
            'inv_profile_status' => 1,
            'reg_source' => 1,
            'advmt_headline' => $record['pitch'] ?? (($record['company'] ?? 'UK business') . ' growth opportunity'),
            'startup_intro' => 'A UK-based early-stage company building practical solutions for its customers.',
            'industry_sector' => $record['industry'] ?? 1,
            'business_website' => 'https://example.test',
            'ofc_city' => $record['city'] ?? null,
            'ofc_country' => 'United Kingdom',
            'estb_year' => 2020 + ($index % 5),
            'emp_count' => '10-50',
            'entity_type' => 'Private Limited Company',
            'business_type' => 'B2B',
            'annual_sales' => 250000 + ($index * 50000),
            'seeking_investors' => 1,
            'seeking_buyers' => 0,
            'seeking_loan' => 0,
            'seeking_mentors' => 1,
            'seeking_accelerators' => 0,
            'seeking_mentorship' => 1,
            'seeking_acquirers' => 0,
            'seeking_incubators' => 0,
            'inv_asking_price' => 500000 + ($index * 100000),
            'inv_stake' => '15%',
            'buyer_sell_price' => 1000000 + ($index * 100000),
            'mentor_req_details' => 'Seeking experienced guidance on strategy and sustainable growth.',
            'business_pitch' => $record['pitch'] ?? null,
            'startup_profile_status' => 1,
            'business_profile_status' => 1,
            'membership_paid' => 0,
            'membership_plan' => 0,
            'created_at' => $now,
            'updated_at' => $now,
        ];
        $table = self::PROFILES[$profileType]['table'];

        if ($profileType === 'startup') {
            $common['emp_count'] = 25;
            $common['business_type'] = 1;
        }

        return array_intersect_key($common, array_flip(Schema::getColumnListing($table)));
    }

    private function ensureUserProfile(
        int $userId,
        int $profileId,
        int $profileType,
        string $profileKey,
        mixed $now
    ): int {
        DB::table('user_profiles')->updateOrInsert(
            ['profile_type' => $profileType, 'profile_str' => $profileKey],
            [
                'user_id' => $userId,
                'profile_id' => $profileId,
                'profile_status' => 1,
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );

        return (int) DB::table('user_profiles')
            ->where('profile_type', $profileType)
            ->where('profile_str', $profileKey)
            ->value('user_prof_id');
    }

    private function ensureCanonicalProfile(
        string $profileType,
        int $legacyType,
        int $profileId,
        int $userId,
        int $userProfileId,
        string $profileKey,
        mixed $now
    ): void {
        if (!Schema::hasTable('profiles')) {
            return;
        }

        DB::table('profiles')->updateOrInsert(
            ['legacy_profile_type' => $legacyType, 'legacy_profile_id' => $profileId],
            [
                'user_id' => $userId,
                'profile_type' => $profileType,
                'legacy_profile_key' => $profileKey,
                'legacy_user_profile_id' => $userProfileId,
                'status' => 1,
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );
    }
}
