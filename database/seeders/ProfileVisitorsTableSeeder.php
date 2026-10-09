<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileVisitorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('profile_visitors')->insert([
            'visitor_id' => 1,
            'visitor_ip' => '192.168.1.1',
            'profile_id' => 1,
            'user_id' => 1,
            'profile_type' => 1,
            'profile_str' => 'BSN0001'
        ]);

        DB::table('profile_visitors')->insert([
            'visitor_id' => 2,
            'visitor_ip' => '10.0.0.55',
            'profile_id' => 2,
            'user_id' => 2,
            'profile_type' => 2,
            'profile_str' => 'INV0002'
        ]);

        DB::table('profile_visitors')->insert([
            'visitor_id' => 3,
            'visitor_ip' => '172.16.0.100',
            'profile_id' => 3,
            'user_id' => 3,
            'profile_type' => 3,
            'profile_str' => 'LEN0003'
        ]);

    }
}