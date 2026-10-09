<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessBookmarksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('business_bookmarks')->insert([
            'bookmark_id' => 1,
            'user_id' => 1,
            'profile_id' => 1,
            'profile_type' => 1,
            'profile_str' => 'BSN0001',
            'bookmark_status' => 0
        ]);

        DB::table('business_bookmarks')->insert([
            'bookmark_id' => 2,
            'user_id' => 2,
            'profile_id' => 2,
            'profile_type' => 2,
            'profile_str' => 'INV0002',
            'bookmark_status' => 1
        ]);

        DB::table('business_bookmarks')->insert([
            'bookmark_id' => 3,
            'user_id' => 3,
            'profile_id' => 3,
            'profile_type' => 3,
            'profile_str' => 'LEN0003',
            'bookmark_status' => 0
        ]);

    }
}