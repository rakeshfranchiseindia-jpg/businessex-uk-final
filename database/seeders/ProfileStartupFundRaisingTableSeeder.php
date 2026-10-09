<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileStartupFundRaisingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('profile_startup_fund_raising')->insert([
            'startup_fund_id' => 1,
            'startup_profile_id' => 1,
            'user_id' => 1,
            'fund_stage' => 1,
            'fund_amount' => '5000000'
        ]);

        DB::table('profile_startup_fund_raising')->insert([
            'startup_fund_id' => 2,
            'startup_profile_id' => 2,
            'user_id' => 2,
            'fund_stage' => 2,
            'fund_amount' => '10000000'
        ]);

        DB::table('profile_startup_fund_raising')->insert([
            'startup_fund_id' => 3,
            'startup_profile_id' => 3,
            'user_id' => 3,
            'fund_stage' => 3,
            'fund_amount' => '25000000'
        ]);

    }
}