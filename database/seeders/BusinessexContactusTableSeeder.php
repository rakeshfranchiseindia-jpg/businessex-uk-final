<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessexContactusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('businessex_contactus')->insert([
            'contact_id' => 1,
            'contact_name' => 'Rajesh Kumar',
            'contact_email' => 'admin@businessex.com',
            'contact_mobile' => '9876543210',
            'contact_comment' => 'Sample description text.'
        ]);

        DB::table('businessex_contactus')->insert([
            'contact_id' => 2,
            'contact_name' => 'Priya Sharma',
            'contact_email' => 'john.doe@example.com',
            'contact_mobile' => '8765432109',
            'contact_comment' => 'Another sample description.'
        ]);

        DB::table('businessex_contactus')->insert([
            'contact_id' => 3,
            'contact_name' => 'Amit Patel',
            'contact_email' => 'priya.sharma@example.com',
            'contact_mobile' => '7654321098',
            'contact_comment' => 'Third sample description.'
        ]);

    }
}