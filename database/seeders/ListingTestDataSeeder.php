<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

/**
 * Adds 12 reusable test accounts, each with an active business, investor,
 * mentor, and startup listing. Safe to run repeatedly.
 *
 * Run with: php artisan db:seed --class=ListingTestDataSeeder
 */
class ListingTestDataSeeder extends Seeder
{
    private const PASSWORD = 'P@ssw0rd@123';

    private const CATEGORIES = [
        'Automobile',
        'Education',
        'Business services',
        'Food & beverage',
        'Finance',
        'Retail',
    ];

    private const LOCATIONS = [
        ['city' => 'London', 'state' => 'Greater London'],
        ['city' => 'Manchester', 'state' => 'Greater Manchester'],
        ['city' => 'Birmingham', 'state' => 'West Midlands'],
        ['city' => 'Leeds', 'state' => 'West Yorkshire'],
        ['city' => 'Bristol', 'state' => 'South West England'],
        ['city' => 'Edinburgh', 'state' => 'Scotland'],
    ];

    public function run(): void
    {
        $categoryRows = DB::table('industry_categories')
            ->where('category_status', 1)
            ->where('parent_id', 0)
            ->whereIn('category_name', self::CATEGORIES)
            ->get(['cat_id', 'category_name'])
            ->keyBy('category_name');

        if ($categoryRows->count() !== count(self::CATEGORIES)) {
            throw new RuntimeException('Listing test data requires all six configured active industry categories.');
        }

        DB::transaction(function () use ($categoryRows): void {
            $now = now();

            for ($index = 1; $index <= 12; $index++) {
                $sequence = str_pad((string) $index, 2, '0', STR_PAD_LEFT);
                $name = "Listing Test User {$sequence}";
                $email = "listing.test.{$sequence}@example.test";
                $phone = '07700900' . str_pad((string) $index, 3, '0', STR_PAD_LEFT);
                $company = "Listing Test Company {$sequence}";
                $location = self::LOCATIONS[($index - 1) % count(self::LOCATIONS)];
                $categoryName = self::CATEGORIES[($index - 1) % count(self::CATEGORIES)];
                $categoryId = (int) $categoryRows[$categoryName]->cat_id;

                DB::table('user_account')->updateOrInsert(
                    ['email' => $email],
                    [
                        'user_rand_id' => "SEED-USER-{$sequence}",
                        'name' => $name,
                        'password' => Hash::make(self::PASSWORD),
                        'mobile' => $phone,
                        'location' => $location['city'],
                        'company_name' => $company,
                        'designation' => 'Owner',
                        'is_active' => 1,
                        'reg_source' => 1,
                        'reg_profile' => 'business',
                        'email_verified_at' => $now,
                        'last_notify_at' => $now,
                        'last_login_at' => $now,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );

                $userId = (int) DB::table('user_account')->where('email', $email)->value('user_id');
                $businessKey = "SEED-BUS-{$sequence}";
                $investorKey = "SEED-INV-{$sequence}";
                $mentorKey = "SEED-MEN-{$sequence}";
                $startupKey = "SEED-STA-{$sequence}";

                $businessId = $this->upsertProfile(
                    'profile_business',
                    'business_id',
                    'business_profile_str',
                    $businessKey,
                    $userId,
                    [
                        'seller_name' => $name,
                        'seller_designation' => 'Owner',
                        'seller_mobile' => $phone,
                        'seller_email' => $email,
                        'advmt_headline' => "{$categoryName} business opportunity {$sequence}",
                        'seller_intro' => "Established {$categoryName} business based in {$location['city']}.",
                        'seller_company' => "{$categoryName} Company {$sequence}",
                        'estb_year' => 2010 + ($index % 15),
                        'emp_count' => '10-50',
                        'entity_type' => 'Private Limited Company',
                        'business_type' => 'B2B',
                        'industry_sector' => $categoryName,
                        'business_website' => "https://business{$sequence}.example.test",
                        'annual_sales' => 250000 + ($index * 25000),
                        'ebitda' => 50000 + ($index * 5000),
                        'company_summary' => "Active test listing in {$categoryName}, located in {$location['city']}.",
                        'ofc_city' => $location['city'],
                        'ofc_state' => $location['state'],
                        'ofc_country' => 'United Kingdom',
                        'business_pitch' => "Discover this {$categoryName} opportunity in {$location['city']}.",
                        'seeking_investors' => $index % 2,
                        'seeking_buyers' => ($index + 1) % 2,
                        'seeking_loan' => $index % 3 === 0 ? 1 : 0,
                        'buyer_sell_price' => 300000 + ($index * 25000),
                        'inv_asking_price' => 50000 + ($index * 10000),
                        'business_profile_status' => 1,
                        'membership_paid' => 1,
                        'membership_plan' => 1,
                    ],
                    $now
                );

                $investorId = $this->upsertProfile(
                    'profile_investor',
                    'investor_id',
                    'inv_profile_str',
                    $investorKey,
                    $userId,
                    [
                        'inv_name' => $name,
                        'inv_email' => $email,
                        'inv_mobile' => $phone,
                        'inv_city' => $location['city'],
                        'inv_state' => $location['state'],
                        'inv_country' => 'United Kingdom',
                        'inv_headline' => "{$categoryName} investor {$sequence}",
                        'inv_intro' => "Interested in {$categoryName} opportunities in {$location['city']}.",
                        'inv_type' => $index % 2,
                        'invest_size_min' => 25000 + ($index * 5000),
                        'invest_size_max' => 150000 + ($index * 10000),
                        'invest_pref' => 1,
                        'invest_stake' => 20 + ($index % 30),
                        'full_acquisition' => $index % 2,
                        'inv_abt_urself' => "Test investor focused on {$categoryName}.",
                        'company_name' => "{$categoryName} Capital {$sequence}",
                        'company_designation' => 'Investment Director',
                        'location_preference' => $location['city'],
                        'sector_preference' => $categoryName,
                        'company_city' => $location['city'],
                        'company_state' => $location['state'],
                        'company_country' => 'United Kingdom',
                        'company_website' => "https://capital{$sequence}.example.test",
                        'company_summary' => "Investment firm supporting {$categoryName} companies.",
                        'inv_profile_status' => 1,
                        'membership_paid' => 1,
                        'membership_plan' => 1,
                        'reg_source' => 1,
                    ],
                    $now
                );

                $mentorId = $this->upsertProfile(
                    'profile_mentors',
                    'mentor_id',
                    'mentor_profile_str',
                    $mentorKey,
                    $userId,
                    [
                        'mentor_name' => $name,
                        'mentor_mobile' => $phone,
                        'mentor_email' => $email,
                        'mentor_location' => $location['city'],
                        'mentor_city' => $location['city'],
                        'mentor_state' => $location['state'],
                        'mentor_country' => 'United Kingdom',
                        'mentor_adv_headline' => "{$categoryName} business growth mentor",
                        'mentor_intro' => "Mentoring {$categoryName} founders and operators in {$location['city']}.",
                        'mentor_occupation' => $index % 2,
                        'mentor_company' => "{$categoryName} Advisory {$sequence}",
                        'mentor_designation' => 'Business Mentor',
                        'mentor_profile_summary' => "Experienced mentor helping {$categoryName} companies grow.",
                        'mentor_profile_status' => 1,
                        'membership_paid' => 1,
                        'membership_plan' => 1,
                    ],
                    $now
                );

                $startupId = $this->upsertProfile(
                    'profile_startups',
                    'startup_id',
                    'startup_profile_str',
                    $startupKey,
                    $userId,
                    [
                        'startup_name' => $name,
                        'startup_designation' => 'Founder',
                        'startup_mobile' => $phone,
                        'startup_email' => $email,
                        'advmt_headline' => "{$categoryName} startup seeking partners {$sequence}",
                        'startup_intro' => "A growing {$categoryName} startup based in {$location['city']}.",
                        'name_of_entity' => "{$categoryName} Startup {$sequence}",
                        'business_type' => 1,
                        'nature_of_entity' => 1,
                        'estb_date' => 2018 + ($index % 8),
                        'emp_count' => 10 + $index,
                        'industry_sector' => $categoryId,
                        'business_website' => "https://startup{$sequence}.example.test",
                        'ofc_city' => $location['city'],
                        'ofc_state' => $location['state'],
                        'ofc_country' => 'United Kingdom',
                        'company_stage' => ['Idea', 'MVP', 'Early Revenue', 'Growth'][$index % 4],
                        'customer_problem' => "A test problem in the {$categoryName} industry.",
                        'product_service' => "A scalable {$categoryName} product for UK customers.",
                        'target_market' => 'United Kingdom',
                        'company_summary' => "{$categoryName} startup listing in {$location['city']}.",
                        'seeking_investors' => 1,
                        'seeking_mentorship' => $index % 2,
                        'seeking_loan' => $index % 3 === 0 ? 1 : 0,
                        'seeking_incubators' => $index % 2,
                        'inv_asking_price' => (string) (40000 + ($index * 10000)),
                        'startup_profile_status' => 1,
                        'membership_paid' => 1,
                        'membership_plan' => 1,
                    ],
                    $now
                );

                $this->linkUserProfile($userId, 1, $businessKey, $businessId, $now);
                $this->linkUserProfile($userId, 2, $investorKey, $investorId, $now);
                $this->linkUserProfile($userId, 4, $mentorKey, $mentorId, $now);
                $this->linkUserProfile($userId, 7, $startupKey, $startupId, $now);
            }
        });

        $this->command?->info(
            'ListingTestDataSeeder: ensured 12 active test users, each with active business, investor, mentor, and startup profiles.'
        );
    }

    private function upsertProfile(
        string $table,
        string $idColumn,
        string $keyColumn,
        string $profileKey,
        int $userId,
        array $values,
        mixed $now
    ): int {
        DB::table($table)->updateOrInsert(
            [$keyColumn => $profileKey],
            $values + [
                'user_id' => $userId,
                $keyColumn => $profileKey,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        return (int) DB::table($table)->where($keyColumn, $profileKey)->value($idColumn);
    }

    private function linkUserProfile(
        int $userId,
        int $profileType,
        string $profileKey,
        int $profileId,
        mixed $now
    ): void {
        DB::table('user_profiles')->updateOrInsert(
            [
                'profile_type' => $profileType,
                'profile_str' => $profileKey,
            ],
            [
                'user_id' => $userId,
                'profile_id' => $profileId,
                'profile_status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }
}
