<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileMembershipsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('profile_memberships')->insert([
            'membership_id' => 2,
            'user_id' => 1,
            'profile_type' => 1,
            'profile_id' => 1,
            'order_no' => 3,
            'amount' => '5000000',
            'membership_type' => 2,
            'payment_source' => 1,
            'payment_comments' => 'This is a sample description providing enough detail to be realistic for testing and development purposes.',
            'interaction_credits' => 5,
            'instant_responses' => 5,
            'activation_date' => '2025-07-15 00:00:00',
            'expiry_date' => '2026-01-15 00:00:00',
            'is_active' => 0,
            'upg_source' => 1
        ]);

        DB::table('profile_memberships')->insert([
            'membership_id' => 1,
            'user_id' => 2,
            'profile_type' => 2,
            'profile_id' => 2,
            'order_no' => 1,
            'amount' => '10000000',
            'membership_type' => 3,
            'payment_source' => 2,
            'payment_comments' => 'A comprehensive overview of business strategies, growth opportunities, and market trends in the current landscape.',
            'interaction_credits' => 15,
            'instant_responses' => 15,
            'activation_date' => '2025-08-15 00:00:00',
            'expiry_date' => '2026-06-15 00:00:00',
            'is_active' => 1,
            'upg_source' => 2
        ]);

        DB::table('profile_memberships')->insert([
            'membership_id' => 3,
            'user_id' => 3,
            'profile_type' => 3,
            'profile_id' => 3,
            'order_no' => 2,
            'amount' => '25000000',
            'membership_type' => 1,
            'payment_source' => 3,
            'payment_comments' => 'Detailed analysis covering key aspects of business operations, market competition, and potential areas for expansion.',
            'interaction_credits' => 25,
            'instant_responses' => 25,
            'activation_date' => '2025-09-15 00:00:00',
            'expiry_date' => '2026-12-15 00:00:00',
            'is_active' => 0,
            'upg_source' => 3
        ]);

    }
}