<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndPrefMentorContactPageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ind_pref_mentor_contact_page')->insert([
            'contact_ind_pref_id' => 1,
            'contact_id' => 1,
            'user_id' => 1,
            'profile_id' => 1,
            'parent_category_id' => 1,
            'sub_category_id' => 1,
            'profile_status' => 0,
            'contact_type' => 'Featured'
        ]);

        DB::table('ind_pref_mentor_contact_page')->insert([
            'contact_ind_pref_id' => 2,
            'contact_id' => 2,
            'user_id' => 2,
            'profile_id' => 2,
            'parent_category_id' => 2,
            'sub_category_id' => 2,
            'profile_status' => 1,
            'contact_type' => 'Trending'
        ]);

        DB::table('ind_pref_mentor_contact_page')->insert([
            'contact_ind_pref_id' => 3,
            'contact_id' => 3,
            'user_id' => 3,
            'profile_id' => 3,
            'parent_category_id' => 3,
            'sub_category_id' => 3,
            'profile_status' => 0,
            'contact_type' => 'Latest'
        ]);

    }
}