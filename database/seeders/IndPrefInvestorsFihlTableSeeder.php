<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndPrefInvestorsFihlTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ind_pref_investors_fihl')->insert([
            'inv_ind_pref_id' => 1,
            'investor_profile_id' => 1,
            'user_id' => 1,
            'parent_category_id' => 1,
            'sub_category_id' => 1,
            'parent_category_id2' => 1,
            'sub_category_id2' => 1,
            'parent_category_id3' => 1,
            'sub_category_id3' => 1,
            'email' => 'admin@businessex.com',
            'invest_min' => 1000.0000,
            'invest_max' => 1000.0000
        ]);

        DB::table('ind_pref_investors_fihl')->insert([
            'inv_ind_pref_id' => 2,
            'investor_profile_id' => 2,
            'user_id' => 2,
            'parent_category_id' => 2,
            'sub_category_id' => 2,
            'parent_category_id2' => 2,
            'sub_category_id2' => 2,
            'parent_category_id3' => 2,
            'sub_category_id3' => 2,
            'email' => 'john.doe@example.com',
            'invest_min' => 1250.0000,
            'invest_max' => 1250.0000
        ]);

        DB::table('ind_pref_investors_fihl')->insert([
            'inv_ind_pref_id' => 3,
            'investor_profile_id' => 3,
            'user_id' => 3,
            'parent_category_id' => 3,
            'sub_category_id' => 3,
            'parent_category_id2' => 3,
            'sub_category_id2' => 3,
            'parent_category_id3' => 3,
            'sub_category_id3' => 3,
            'email' => 'priya.sharma@example.com',
            'invest_min' => 1500.0000,
            'invest_max' => 1500.0000
        ]);

    }
}