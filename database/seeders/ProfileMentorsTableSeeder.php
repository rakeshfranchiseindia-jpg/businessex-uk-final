<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileMentorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('profile_mentors')->insert([
            'mentor_id' => 1,
            'mentor_profile_str' => 'BSN0001',
            'user_id' => 1,
            'mentor_name' => 'Rajesh Kumar',
            'mentor_mobile' => '9876543210',
            'mentor_email' => 'admin@businessex.com',
            'mentor_location' => '123 Business Park, Andheri East',
            'mentor_city' => 'Mumbai',
            'mentor_state' => 'Maharashtra',
            'mentor_country' => 'India',
            'mentor_adv_headline' => 'Getting Started with Business Growth',
            'mentor_intro' => 'This is a sample description providing enough detail to be realistic for testing and development purposes.',
            'mentor_occupation' => 1,
            'mentor_company' => 'Sample string data',
            'mentor_designation' => 'CEO',
            'mentor_profile_summary' => 'This is a sample description providing enough detail to be realistic for testing and development purposes.',
            'mentor_profile_pic' => 'uploads/images/sample1.jpg',
            'mentor_linkedin' => 'https://example.com',
            'mentor_profile_status' => 0,
            'membership_paid' => 2,
            'membership_plan' => 2,
            'mentor_profile_pic_name' => 'uploads/images/sample1.jpg',
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

        DB::table('profile_mentors')->insert([
            'mentor_id' => 2,
            'mentor_profile_str' => 'INV0002',
            'user_id' => 2,
            'mentor_name' => 'Priya Sharma',
            'mentor_mobile' => '8765432109',
            'mentor_email' => 'john.doe@example.com',
            'mentor_location' => '456 Tech Hub, Whitefield',
            'mentor_city' => 'Delhi',
            'mentor_state' => 'Delhi',
            'mentor_country' => 'United States',
            'mentor_adv_headline' => 'Top Investment Strategies for 2025',
            'mentor_intro' => 'A comprehensive overview of business strategies, growth opportunities, and market trends in the current landscape.',
            'mentor_occupation' => 2,
            'mentor_company' => 'Another sample entry',
            'mentor_designation' => 'CTO',
            'mentor_profile_summary' => 'A comprehensive overview of business strategies, growth opportunities, and market trends in the current landscape.',
            'mentor_profile_pic' => 'uploads/images/sample2.jpg',
            'mentor_linkedin' => 'https://linkedin.com/in/example',
            'mentor_profile_status' => 1,
            'membership_paid' => 1,
            'membership_plan' => 1,
            'mentor_profile_pic_name' => 'uploads/images/sample2.jpg',
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

        DB::table('profile_mentors')->insert([
            'mentor_id' => 3,
            'mentor_profile_str' => 'LEN0003',
            'user_id' => 3,
            'mentor_name' => 'Amit Patel',
            'mentor_mobile' => '7654321098',
            'mentor_email' => 'priya.sharma@example.com',
            'mentor_location' => '789 Corporate Tower, Connaught Place',
            'mentor_city' => 'Bangalore',
            'mentor_state' => 'Karnataka',
            'mentor_country' => 'Singapore',
            'mentor_adv_headline' => 'How to Scale Your Startup',
            'mentor_intro' => 'Detailed analysis covering key aspects of business operations, market competition, and potential areas for expansion.',
            'mentor_occupation' => 3,
            'mentor_company' => 'Third sample value',
            'mentor_designation' => 'Managing Director',
            'mentor_profile_summary' => 'Detailed analysis covering key aspects of business operations, market competition, and potential areas for expansion.',
            'mentor_profile_pic' => 'uploads/images/sample3.jpg',
            'mentor_linkedin' => 'https://company.com',
            'mentor_profile_status' => 0,
            'membership_paid' => 1,
            'membership_plan' => 2,
            'mentor_profile_pic_name' => 'uploads/images/sample3.jpg',
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