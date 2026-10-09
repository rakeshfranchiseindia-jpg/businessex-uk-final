<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactCommentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('contact_comment')->insert([
            'id' => 1,
            'contact_id' => 1,
            'contact_type' => 1,
            'commented_by' => 3,
            'contact_status' => 0,
            'reminder' => '2025-01-01 10:30:00',
            'reminder_sent' => 0,
            'contact_response' => 5,
            'comment' => 'This is a sample description providing enough detail to be realistic for testing and development purposes.',
            'comment_date' => now(),   // ✅ current date
            'updated_date' => now(),   // ✅ current date
            'status' => 0
        ]);

        DB::table('contact_comment')->insert([
            'id' => 2,
            'contact_id' => 2,
            'contact_type' => 2,
            'commented_by' => 2,
            'contact_status' => 1,
            'reminder' => '2025-02-02 10:30:00',
            'reminder_sent' => 1,
            'contact_response' => 15,
            'comment' => 'A comprehensive overview of business strategies, growth opportunities, and market trends in the current landscape.',
            'comment_date' => now(),   // ✅ current date
            'updated_date' => now(),
            'status' => 1
        ]);

        DB::table('contact_comment')->insert([
            'id' => 3,
            'contact_id' => 3,
            'contact_type' => 3,
            'commented_by' => 1,
            'contact_status' => 0,
            'reminder' => '2025-03-03 10:30:00',
            'reminder_sent' => 0,
            'contact_response' => 25,
            'comment' => 'Detailed analysis covering key aspects of business operations, market competition, and potential areas for expansion.',
            'comment_date' => now(),   // ✅ current date
            'updated_date' => now(),
            'status' => 0
        ]);
    }
}