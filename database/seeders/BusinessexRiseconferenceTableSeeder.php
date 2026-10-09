<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessexRiseconferenceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('businessex_riseconference')->insert([
            'contact_id' => 1,
            'ref_type' => 'Featured',
            'contact_name' => 'Rajesh Kumar',
            'contact_last' => 'Sample string data',
            'contact_email' => 'admin@businessex.com',
            'contact_mobile' => '9876543210',
            'contact_company' => 'Sample string data',
            'contact_country' => 'India'
        ]);

        DB::table('businessex_riseconference')->insert([
            'contact_id' => 2,
            'ref_type' => 'Trending',
            'contact_name' => 'Priya Sharma',
            'contact_last' => 'Another sample entry',
            'contact_email' => 'john.doe@example.com',
            'contact_mobile' => '8765432109',
            'contact_company' => 'Another sample entry',
            'contact_country' => 'United States'
        ]);

        DB::table('businessex_riseconference')->insert([
            'contact_id' => 3,
            'ref_type' => 'Latest',
            'contact_name' => 'Amit Patel',
            'contact_last' => 'Third sample value',
            'contact_email' => 'priya.sharma@example.com',
            'contact_mobile' => '7654321098',
            'contact_company' => 'Third sample value',
            'contact_country' => 'Singapore'
        ]);

    }
}