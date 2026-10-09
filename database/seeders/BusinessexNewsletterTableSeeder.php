<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessexNewsletterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('businessex_newsletter')->insert([
            'newsletter_id' => 1,
            'user_id' => 1,
            'email' => 'admin@businessex.com',
            'status' => 'S',
            'unsubscribe_reason' => 'Sample description text.'
        ]);

        DB::table('businessex_newsletter')->insert([
            'newsletter_id' => 2,
            'user_id' => 2,
            'email' => 'john.doe@example.com',
            'status' => 'P',
            'unsubscribe_reason' => 'Another sample description.'
        ]);

        DB::table('businessex_newsletter')->insert([
            'newsletter_id' => 3,
            'user_id' => 3,
            'email' => 'priya.sharma@example.com',
            'status' => 'U',
            'unsubscribe_reason' => 'Third sample description.'
        ]);

    }
}