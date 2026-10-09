<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MobileVerificationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mobile_verification')->insert([
            'mob_verify_id' => 1,
            'user_id' => 1,
            'mobile_no' => '9876543210',
            'otp_code' => 'abcdefghi',
            'smspg_response' => 'This is a sample description providing enough detail to be realistic for testing and development purposes.',
            'is_verified' => 0,
            'verified_at' => '2025-01-01 10:30:00'
        ]);

        DB::table('mobile_verification')->insert([
            'mob_verify_id' => 2,
            'user_id' => 2,
            'mobile_no' => '8765432109',
            'otp_code' => 'abcdefghi',
            'smspg_response' => 'A comprehensive overview of business strategies, growth opportunities, and market trends in the current landscape.',
            'is_verified' => 1,
            'verified_at' => '2025-02-02 10:30:00'
        ]);

        DB::table('mobile_verification')->insert([
            'mob_verify_id' => 3,
            'user_id' => 3,
            'mobile_no' => '7654321098',
            'otp_code' => 'abcdefghi',
            'smspg_response' => 'Detailed analysis covering key aspects of business operations, market competition, and potential areas for expansion.',
            'is_verified' => 0,
            'verified_at' => '2025-03-03 10:30:00'
        ]);

    }
}