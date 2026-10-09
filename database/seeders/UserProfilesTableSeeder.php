<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserProfilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_profiles')->insert([
            'user_prof_id' => 1,
            'user_id' => 1,
            'profile_id' => 1,
            'profile_type' => 1,
            'profile_str' => 'BSN0001',
            'profile_status' => 0
        ]);

        DB::table('user_profiles')->insert([
            'user_prof_id' => 2,
            'user_id' => 2,
            'profile_id' => 2,
            'profile_type' => 2,
            'profile_str' => 'INV0002',
            'profile_status' => 1
        ]);

        DB::table('user_profiles')->insert([
            'user_prof_id' => 3,
            'user_id' => 3,
            'profile_id' => 3,
            'profile_type' => 3,
            'profile_str' => 'LEN0003',
            'profile_status' => 0
        ]);

    }
}