<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminProfileRejectedTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admin_profile_rejected')->insert([
            'prof_reject_id' => 1,
            'profile_type' => 'Featured',
            'profile_id' => 1,
            'admin_email' => 'admin@businessex.com',
            'rejected_reason' => 'Sample description text.'
        ]);

        DB::table('admin_profile_rejected')->insert([
            'prof_reject_id' => 2,
            'profile_type' => 'Trending',
            'profile_id' => 2,
            'admin_email' => 'john.doe@example.com',
            'rejected_reason' => 'Another sample description.'
        ]);

        DB::table('admin_profile_rejected')->insert([
            'prof_reject_id' => 3,
            'profile_type' => 'Latest',
            'profile_id' => 3,
            'admin_email' => 'priya.sharma@example.com',
            'rejected_reason' => 'Third sample description.'
        ]);

    }
}