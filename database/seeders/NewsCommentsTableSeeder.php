<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsCommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('news_comments')->insert([
            'comment_id' => 1,
            'news_id' => 1,
            'comment_name' => 'Sample description text.',
            'comment_email' => 'admin@businessex.com',
            'comment_detail' => 'This is a sample description providing enough detail to be realistic for testing and development purposes.',
            'comment_status' => 3
        ]);

        DB::table('news_comments')->insert([
            'comment_id' => 2,
            'news_id' => 2,
            'comment_name' => 'Another sample description.',
            'comment_email' => 'john.doe@example.com',
            'comment_detail' => 'A comprehensive overview of business strategies, growth opportunities, and market trends in the current landscape.',
            'comment_status' => 2
        ]);

        DB::table('news_comments')->insert([
            'comment_id' => 3,
            'news_id' => 3,
            'comment_name' => 'Third sample description.',
            'comment_email' => 'priya.sharma@example.com',
            'comment_detail' => 'Detailed analysis covering key aspects of business operations, market competition, and potential areas for expansion.',
            'comment_status' => 1
        ]);

    }
}