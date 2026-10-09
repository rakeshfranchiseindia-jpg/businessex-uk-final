<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileBrokerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('profile_broker')->insert([
            'broker_id' => 1,
            'broker_profile_str' => 'BSN0001',
            'user_id' => 1,
            'broker_name' => 'Rajesh Kumar',
            'broker_mobile' => '9876543210',
            'broker_email' => 'admin@businessex.com',
            'broker_profile_type' => 1,
            'broker_company' => 'Sample string data',
            'estb_year' => 2010,
            'emp_count' => 2,
            'company_city' => 'Mumbai',
            'company_state' => 'Maharashtra',
            'company_country' => 'India',
            'company_website' => 'https://example.com',
            'ofc_city' => 'Mumbai',
            'ofc_state' => 'Maharashtra',
            'ofc_country' => 'India',
            'ofc_pincode' => '400001',
            'prof_summary' => 'Sample description text.',
            'prof_exp_year' => 1,
            'broker_company_logo' => 'uploads/images/sample1.jpg',
            'broker_profile_status' => 0,
            'membership_paid' => 1,
            'membership_plan' => 1,
            'contact_response' => 5,
            'utm_source' => 'Featured',
            'contact_status' => 0,
            'activated_by' => 1,
            'activated_at' => '2025-01-01 10:30:00',
            'last_login_at' => '2025-01-01 10:30:00'
        ]);

        DB::table('profile_broker')->insert([
            'broker_id' => 2,
            'broker_profile_str' => 'INV0002',
            'user_id' => 2,
            'broker_name' => 'Priya Sharma',
            'broker_mobile' => '8765432109',
            'broker_email' => 'john.doe@example.com',
            'broker_profile_type' => 2,
            'broker_company' => 'Another sample entry',
            'estb_year' => 2013,
            'emp_count' => 3,
            'company_city' => 'Delhi',
            'company_state' => 'Delhi',
            'company_country' => 'United States',
            'company_website' => 'https://linkedin.com/in/example',
            'ofc_city' => 'Delhi',
            'ofc_state' => 'Delhi',
            'ofc_country' => 'United States',
            'ofc_pincode' => '110001',
            'prof_summary' => 'Another sample description.',
            'prof_exp_year' => 2,
            'broker_company_logo' => 'uploads/images/sample2.jpg',
            'broker_profile_status' => 1,
            'membership_paid' => 2,
            'membership_plan' => 2,
            'contact_response' => 15,
            'utm_source' => 'Trending',
            'contact_status' => 1,
            'activated_by' => 2,
            'activated_at' => '2025-02-02 10:30:00',
            'last_login_at' => '2025-02-02 10:30:00'
        ]);

        DB::table('profile_broker')->insert([
            'broker_id' => 3,
            'broker_profile_str' => 'LEN0003',
            'user_id' => 3,
            'broker_name' => 'Amit Patel',
            'broker_mobile' => '7654321098',
            'broker_email' => 'priya.sharma@example.com',
            'broker_profile_type' => 3,
            'broker_company' => 'Third sample value',
            'estb_year' => 2016,
            'emp_count' => 4,
            'company_city' => 'Bangalore',
            'company_state' => 'Karnataka',
            'company_country' => 'Singapore',
            'company_website' => 'https://company.com',
            'ofc_city' => 'Bangalore',
            'ofc_state' => 'Karnataka',
            'ofc_country' => 'Singapore',
            'ofc_pincode' => '560001',
            'prof_summary' => 'Third sample description.',
            'prof_exp_year' => 3,
            'broker_company_logo' => 'uploads/images/sample3.jpg',
            'broker_profile_status' => 0,
            'membership_paid' => 3,
            'membership_plan' => 3,
            'contact_response' => 25,
            'utm_source' => 'Latest',
            'contact_status' => 0,
            'activated_by' => 3,
            'activated_at' => '2025-03-03 10:30:00',
            'last_login_at' => '2025-03-03 10:30:00'
        ]);

    }
}