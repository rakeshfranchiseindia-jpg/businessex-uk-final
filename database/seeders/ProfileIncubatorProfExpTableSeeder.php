<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileIncubatorProfExpTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('profile_incubator_prof_exp')->insert([
            'incubator_mgmt_id' => 1,
            'incubator_profile_id' => 1,
            'user_id' => 1,
            'exp_year' => 2,
            'exp_sector' => 1
        ]);

        DB::table('profile_incubator_prof_exp')->insert([
            'incubator_mgmt_id' => 2,
            'incubator_profile_id' => 2,
            'user_id' => 2,
            'exp_year' => 3,
            'exp_sector' => 2
        ]);

        DB::table('profile_incubator_prof_exp')->insert([
            'incubator_mgmt_id' => 3,
            'incubator_profile_id' => 3,
            'user_id' => 3,
            'exp_year' => 4,
            'exp_sector' => 3
        ]);

    }
}