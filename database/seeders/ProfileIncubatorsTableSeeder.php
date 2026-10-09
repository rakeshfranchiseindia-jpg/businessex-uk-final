<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileIncubatorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('profile_incubators')->insert([
            'incubator_id' => 1,
            'incubator_profile_str' => 'BSN0001',
            'user_id' => 1,
            'incubator_name' => 'Rajesh Kumar',
            'incubator_mobile' => '9876543210',
            'incubator_email' => 'admin@businessex.com',
            'incubator_location' => 1,
            'incubator_city' => 'Mumbai',
            'incubator_state' => 'Maharashtra',
            'incubator_country' => 'India',
            'incubator_adv_headline' => 'Getting Started with Business Growth',
            'incubator_intro' => 'This is a sample description providing enough detail to be realistic for testing and development purposes.',
            'incubator_company' => 'Sample string data',
            'incubator_designation' => 'CEO',
            'incubator_profile_summary' => 'This is a sample description providing enough detail to be realistic for testing and development purposes.',
            'incubator_company_logo' => 'uploads/images/sample1.jpg',
            'estb_year' => 2010,
            'company_city' => 'Mumbai',
            'company_state' => 'Maharashtra',
            'company_country' => 'India',
            'company_pincode' => '400001',
            'signature' => 'uploads/images/sample1.jpg',
            'company_website' => 'https://example.com',
            'membership_paid' => 1,
            'membership_plan' => 1,
            'incubator_profile_status' => 0,
            'trackid' => 'TRK1000',
            'utm_source' => 'Featured',
            'utm_medium' => 'google',
            'utm_campaign' => 'google',
            'contact_response' => 5,
            'contact_status' => 0,
            'activated_by' => 1,
            'activated_at' => '2025-01-01 10:30:00',
            'last_login_at' => '2025-01-01 10:30:00'
        ]);

        DB::table('profile_incubators')->insert([
            'incubator_id' => 2,
            'incubator_profile_str' => 'INV0002',
            'user_id' => 2,
            'incubator_name' => 'Priya Sharma',
            'incubator_mobile' => '8765432109',
            'incubator_email' => 'john.doe@example.com',
            'incubator_location' => 2,
            'incubator_city' => 'Delhi',
            'incubator_state' => 'Delhi',
            'incubator_country' => 'United States',
            'incubator_adv_headline' => 'Top Investment Strategies for 2025',
            'incubator_intro' => 'A comprehensive overview of business strategies, growth opportunities, and market trends in the current landscape.',
            'incubator_company' => 'Another sample entry',
            'incubator_designation' => 'CTO',
            'incubator_profile_summary' => 'A comprehensive overview of business strategies, growth opportunities, and market trends in the current landscape.',
            'incubator_company_logo' => 'uploads/images/sample2.jpg',
            'estb_year' => 2013,
            'company_city' => 'Delhi',
            'company_state' => 'Delhi',
            'company_country' => 'United States',
            'company_pincode' => '110001',
            'signature' => 'uploads/images/sample2.jpg',
            'company_website' => 'https://linkedin.com/in/example',
            'membership_paid' => 2,
            'membership_plan' => 2,
            'incubator_profile_status' => 1,
            'trackid' => 'TRK1001',
            'utm_source' => 'Trending',
            'utm_medium' => 'facebook',
            'utm_campaign' => 'facebook',
            'contact_response' => 15,
            'contact_status' => 1,
            'activated_by' => 2,
            'activated_at' => '2025-02-02 10:30:00',
            'last_login_at' => '2025-02-02 10:30:00'
        ]);

        DB::table('profile_incubators')->insert([
            'incubator_id' => 3,
            'incubator_profile_str' => 'LEN0003',
            'user_id' => 3,
            'incubator_name' => 'Amit Patel',
            'incubator_mobile' => '7654321098',
            'incubator_email' => 'priya.sharma@example.com',
            'incubator_location' => 3,
            'incubator_city' => 'Bangalore',
            'incubator_state' => 'Karnataka',
            'incubator_country' => 'Singapore',
            'incubator_adv_headline' => 'How to Scale Your Startup',
            'incubator_intro' => 'Detailed analysis covering key aspects of business operations, market competition, and potential areas for expansion.',
            'incubator_company' => 'Third sample value',
            'incubator_designation' => 'Managing Director',
            'incubator_profile_summary' => 'Detailed analysis covering key aspects of business operations, market competition, and potential areas for expansion.',
            'incubator_company_logo' => 'uploads/images/sample3.jpg',
            'estb_year' => 2016,
            'company_city' => 'Bangalore',
            'company_state' => 'Karnataka',
            'company_country' => 'Singapore',
            'company_pincode' => '560001',
            'signature' => 'uploads/images/sample3.jpg',
            'company_website' => 'https://company.com',
            'membership_paid' => 3,
            'membership_plan' => 3,
            'incubator_profile_status' => 0,
            'trackid' => 'TRK1002',
            'utm_source' => 'Latest',
            'utm_medium' => 'linkedin',
            'utm_campaign' => 'linkedin',
            'contact_response' => 25,
            'contact_status' => 0,
            'activated_by' => 3,
            'activated_at' => '2025-03-03 10:30:00',
            'last_login_at' => '2025-03-03 10:30:00'
        ]);

    }
}