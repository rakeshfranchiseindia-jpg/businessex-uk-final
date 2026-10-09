<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileBusinessMgmtTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('profile_business_mgmt')->insert([
            'business_mgmt_id' => 1,
            'business_profile_id' => 1,
            'user_id' => 1,
            'mgmt_name' => 'Rajesh Kumar',
            'mgmt_designation' => 'CEO',
            'mgmt_email' => 'admin@businessex.com'
        ]);

        DB::table('profile_business_mgmt')->insert([
            'business_mgmt_id' => 2,
            'business_profile_id' => 2,
            'user_id' => 2,
            'mgmt_name' => 'Priya Sharma',
            'mgmt_designation' => 'CTO',
            'mgmt_email' => 'john.doe@example.com'
        ]);

        DB::table('profile_business_mgmt')->insert([
            'business_mgmt_id' => 3,
            'business_profile_id' => 3,
            'user_id' => 3,
            'mgmt_name' => 'Amit Patel',
            'mgmt_designation' => 'Managing Director',
            'mgmt_email' => 'priya.sharma@example.com'
        ]);

    }
}