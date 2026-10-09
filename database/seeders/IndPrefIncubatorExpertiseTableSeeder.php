<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndPrefIncubatorExpertiseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ind_pref_incubator_expertise')->insert([
            'incubator_ind_pref_id' => 1,
            'incubator_profile_id' => 1,
            'user_id' => 1,
            'parent_category_id' => 1,
            'sub_category_id' => 1,
            'profile_status' => 0
        ]);

        DB::table('ind_pref_incubator_expertise')->insert([
            'incubator_ind_pref_id' => 2,
            'incubator_profile_id' => 2,
            'user_id' => 2,
            'parent_category_id' => 2,
            'sub_category_id' => 2,
            'profile_status' => 1
        ]);

        DB::table('ind_pref_incubator_expertise')->insert([
            'incubator_ind_pref_id' => 3,
            'incubator_profile_id' => 3,
            'user_id' => 3,
            'parent_category_id' => 3,
            'sub_category_id' => 3,
            'profile_status' => 0
        ]);

    }
}