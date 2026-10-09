<?php

namespace Database\Seeders;

use App\Models\Bookmark;
use App\Models\ProfileBusiness;
use App\Models\ProfileLender;
use App\Models\ProfileMentor;
use App\Models\ProfileStartup;
use App\Models\ProfileVisitor;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;

/**
 * Demo/test data so the "My Profiles" dashboard widgets (New Listings /
 * Saved Searches / Search History / Top 5 Recommendations) have something
 * to show. Existing project data is almost entirely inactive/orphaned
 * (business_profile_status = 0, visitor rows pointing at profiles that
 * don't exist, etc.) — this only ADDS new rows, it does not touch or
 * delete anything that already exists. Idempotent — safe to re-run.
 *
 * Run with: php artisan db:seed --class=MyProfilesDemoSeeder
 */
class MyProfilesDemoSeeder extends Seeder
{
    public function run(): void
    {
        $demoUserId = 1;
        $activeStatus = config('constants.ProfileStatus.Active');
        $businessType = config('constants.profileTypes.Business');
        $lenderType = config('constants.profileTypes.Lender');
        $mentorType = config('constants.profileTypes.Mentor');
        $startupType = config('constants.profileTypes.Startup');

        // ---- 1 active Business ----
        $business = ProfileBusiness::firstOrCreate(['business_profile_str' => 'DEMOBSN01'], [
            'user_id' => $demoUserId,
            'seller_name' => 'Demo Seller',
            'seller_mobile' => '9999900001',
            'seller_email' => 'demo.seller@example.com',
            'seller_designation' => 'Owner',
            'advmt_headline' => 'Profitable Cloud Kitchen Chain For Sale',
            'seller_intro' => 'Well-established cloud kitchen brand with 4 outlets, looking for a buyer.',
            'seller_company' => 'Demo Kitchens Pvt Ltd',
            'industry_sector' => 6, // Food & Beverage per randomSubCategoryImage's known set
            'ofc_city' => 'Delhi',
            'ofc_state' => 'DL',
            'ofc_country' => 'India',
            'seeking_buyers' => 1,
            'buyer_sell_price' => 15000000,
            'buyer_sell_reason' => 'Relocating abroad',
            'membership_paid' => 1,
            'membership_plan' => 2,
            'business_profile_status' => $activeStatus,
        ]);
        UserProfile::firstOrCreate(
            ['profile_type' => $businessType, 'profile_str' => 'DEMOBSN01'],
            ['user_id' => $demoUserId, 'profile_id' => $business->business_id, 'profile_status' => $activeStatus]
        );

        // ---- 1 active Startup ----
        $startup = ProfileStartup::firstOrCreate(['startup_profile_str' => 'DEMOSTP01'], [
            'user_id' => $demoUserId,
            'startup_name' => 'Demo Founder',
            'startup_mobile' => '9999900002',
            'startup_email' => 'demo.startup@example.com',
            'advmt_headline' => 'AI-Powered Logistics Startup Seeking Funding',
            'startup_intro' => 'Route-optimisation SaaS for last-mile delivery fleets, live in 3 cities.',
            'industry_sector' => 11, // Finance-adjacent per known category set
            'ofc_city' => 'Bangalore',
            'ofc_state' => 'KA',
            'ofc_country' => 'India',
            'seeking_investors' => 1,
            'inv_asking_price' => 20000000,
            'membership_paid' => 1,
            'membership_plan' => 1,
            'startup_profile_status' => $activeStatus,
        ]);
        UserProfile::firstOrCreate(
            ['profile_type' => $startupType, 'profile_str' => 'DEMOSTP01'],
            ['user_id' => $demoUserId, 'profile_id' => $startup->startup_id, 'profile_status' => $activeStatus]
        );

        // ---- 1 active Mentor ----
        $mentor = ProfileMentor::firstOrCreate(['mentor_profile_str' => 'DEMOMNT01'], [
            'user_id' => $demoUserId,
            'mentor_name' => 'Demo Mentor',
            'mentor_mobile' => '9999900003',
            'mentor_email' => 'demo.mentor@example.com',
            'mentor_city' => 'Mumbai',
            'mentor_state' => 'MH',
            'mentor_country' => 'India',
            'mentor_adv_headline' => 'Growth & Fundraising Mentor for Early-Stage Startups',
            'mentor_intro' => '15+ years scaling D2C brands, ex-VP Growth at two unicorns.',
            'membership_paid' => 1,
            'membership_plan' => 3,
            'mentor_profile_status' => $activeStatus,
        ]);
        UserProfile::firstOrCreate(
            ['profile_type' => $mentorType, 'profile_str' => 'DEMOMNT01'],
            ['user_id' => $demoUserId, 'profile_id' => $mentor->mentor_id, 'profile_status' => $activeStatus]
        );

        // ---- 1 active Lender ----
        $lender = ProfileLender::firstOrCreate(['lender_profile_str' => 'DEMOLND01'], [
            'user_id' => $demoUserId,
            'lender_name' => 'Demo Lender',
            'lender_mobile' => '9999900004',
            'lender_email' => 'demo.lender@example.com',
            'lender_city' => 'Chennai',
            'lender_state' => 'TN',
            'lender_country' => 'India',
            'lender_adv_headline' => 'Working Capital Loans For SMEs',
            'lender_intro' => 'NBFC offering unsecured working-capital loans to profitable SMEs.',
            'lender_profile_status' => $activeStatus,
        ]);
        UserProfile::firstOrCreate(
            ['profile_type' => $lenderType, 'profile_str' => 'DEMOLND01'],
            ['user_id' => $demoUserId, 'profile_id' => $lender->lender_id, 'profile_status' => $activeStatus]
        );

        // ---- Saved Searches (Bookmarks) for demo user: business + startup ----
        Bookmark::firstOrCreate(
            ['user_id' => $demoUserId, 'profile_type' => $businessType, 'profile_str' => 'DEMOBSN01'],
            ['profile_id' => $business->business_id, 'bookmark_status' => $activeStatus]
        );
        Bookmark::firstOrCreate(
            ['user_id' => $demoUserId, 'profile_type' => $startupType, 'profile_str' => 'DEMOSTP01'],
            ['profile_id' => $startup->startup_id, 'bookmark_status' => $activeStatus]
        );

        // ---- Search History (Profile Visitors) for demo user: mentor + lender ----
        ProfileVisitor::firstOrCreate([
            'user_id' => $demoUserId,
            'profile_type' => $mentorType,
            'profile_str' => 'DEMOMNT01',
            'profile_id' => $mentor->mentor_id,
        ], ['visitor_ip' => '127.0.0.1']);
        ProfileVisitor::firstOrCreate([
            'user_id' => $demoUserId,
            'profile_type' => $lenderType,
            'profile_str' => 'DEMOLND01',
            'profile_id' => $lender->lender_id,
        ], ['visitor_ip' => '127.0.0.1']);

        $this->command->info('MyProfilesDemoSeeder: ensured 1 active Business/Startup/Mentor/Lender + bookmarks + visit history for user_id=' . $demoUserId);
    }
}
