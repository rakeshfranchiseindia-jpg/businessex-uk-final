<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactBrokerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('contact_broker')->insert([
            'contact_id' => 1,
            'profile_str' => 'BSN0001',
            'user_id' => 1,
            'profile_id' => 1,
            'contact_name' => 'Rajesh Kumar',
            'contact_designation' => 'CEO',
            'contact_mobile' => '9876543210',
            'contact_email' => 'admin@businessex.com',
            'contact_company' => 'Sample string data',
            'contact_investment' => '5000000',
            'contact_comment' => 'Sample description text.',
            'contact_viewed' => 1
        ]);

        DB::table('contact_broker')->insert([
            'contact_id' => 2,
            'profile_str' => 'INV0002',
            'user_id' => 2,
            'profile_id' => 2,
            'contact_name' => 'Priya Sharma',
            'contact_designation' => 'CTO',
            'contact_mobile' => '8765432109',
            'contact_email' => 'john.doe@example.com',
            'contact_company' => 'Another sample entry',
            'contact_investment' => '15000000',
            'contact_comment' => 'Another sample description.',
            'contact_viewed' => 2
        ]);

        DB::table('contact_broker')->insert([
            'contact_id' => 3,
            'profile_str' => 'LEN0003',
            'user_id' => 3,
            'profile_id' => 3,
            'contact_name' => 'Amit Patel',
            'contact_designation' => 'Managing Director',
            'contact_mobile' => '7654321098',
            'contact_email' => 'priya.sharma@example.com',
            'contact_company' => 'Third sample value',
            'contact_investment' => '50000000',
            'contact_comment' => 'Third sample description.',
            'contact_viewed' => 3
        ]);

    }
}