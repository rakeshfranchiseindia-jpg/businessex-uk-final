<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RequestContactTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('request_contact')->insert([
            'request_id' => 1,
            'profile_str' => 'BSN0001',
            'receiver' => 1,
            'sender' => 1,
            'receiver_profile_type' => 1,
            'sender_profile_type' => 1,
            'status' => '1',
            'viewed_status' => 0,
            'msg' => 'This is a sample description providing enough detail to be realistic for testing and development purposes.',
            'timestamp' => '1720000000'
        ]);

        DB::table('request_contact')->insert([
            'request_id' => 2,
            'profile_str' => 'INV0002',
            'receiver' => 2,
            'sender' => 2,
            'receiver_profile_type' => 2,
            'sender_profile_type' => 2,
            'status' => '2',
            'viewed_status' => 1,
            'msg' => 'A comprehensive overview of business strategies, growth opportunities, and market trends in the current landscape.',
            'timestamp' => '1720001000'
        ]);

        DB::table('request_contact')->insert([
            'request_id' => 3,
            'profile_str' => 'LEN0003',
            'receiver' => 3,
            'sender' => 3,
            'receiver_profile_type' => 3,
            'sender_profile_type' => 3,
            'status' => '3',
            'viewed_status' => 0,
            'msg' => 'Detailed analysis covering key aspects of business operations, market competition, and potential areas for expansion.',
            'timestamp' => '1720002000'
        ]);

    }
}