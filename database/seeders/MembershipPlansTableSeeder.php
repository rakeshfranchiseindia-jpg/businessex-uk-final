<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MembershipPlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('membership_plans')->insert([
            'plan_id' => 1,
            'plan_type' => 1,
            'plan_name' => 'Basic Plan',
            'plan_desc' => 'This is a sample description providing enough detail to be realistic for testing and development purposes.',
            'profile_type' => 1,
            'profile_name' => 'Business',
            'validity_in_days' => 5,
            'plan_amount' => '5000000',
            'interaction_credits' => 5,
            'instant_responses' => 5,
            'is_active' => 0,
            'deactivated_at' => '2025-01-01 10:30:00'
        ]);

        DB::table('membership_plans')->insert([
            'plan_id' => 2,
            'plan_type' => 2,
            'plan_name' => 'Premium Plan',
            'plan_desc' => 'A comprehensive overview of business strategies, growth opportunities, and market trends in the current landscape.',
            'profile_type' => 2,
            'profile_name' => 'Investor',
            'validity_in_days' => 15,
            'plan_amount' => '10000000',
            'interaction_credits' => 15,
            'instant_responses' => 15,
            'is_active' => 1,
            'deactivated_at' => '2025-02-02 10:30:00'
        ]);

        DB::table('membership_plans')->insert([
            'plan_id' => 3,
            'plan_type' => 3,
            'plan_name' => 'Gold Plan',
            'plan_desc' => 'Detailed analysis covering key aspects of business operations, market competition, and potential areas for expansion.',
            'profile_type' => 3,
            'profile_name' => 'Startup',
            'validity_in_days' => 25,
            'plan_amount' => '25000000',
            'interaction_credits' => 25,
            'instant_responses' => 25,
            'is_active' => 0,
            'deactivated_at' => '2025-03-03 10:30:00'
        ]);

    }
}