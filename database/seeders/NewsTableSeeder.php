<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('news')->insert([
            'newsID' => 1,
            'homeTitle' => 'Getting Started with Business Growth',
            'title' => 'Getting Started with Business Growth',
            'shortDesc' => 'Sample description text.',
            'content' => 'This is a sample description providing enough detail to be realistic for testing and development purposes.',
            'image' => 'uploads/images/sample1.jpg',
            'tags' => 'business, startup, investment',
            'newskeywords' => 'business, startup, investment',
            'totalComment' => 23,
            'totalVotes' => 1,
            'views' => 5,
            'status' => 'A',
            'seoTitle' => 'Getting Started with Business Growth',
            'seoKeywords' => 'business, startup, investment',
            'seoDescription' => '192.168.1.1',
            'news_date' => '2025-01-01 10:30:00'
        ]);

        DB::table('news')->insert([
            'newsID' => 2,
            'homeTitle' => 'Top Investment Strategies for 2025',
            'title' => 'Top Investment Strategies for 2025',
            'shortDesc' => 'Another sample description.',
            'content' => 'A comprehensive overview of business strategies, growth opportunities, and market trends in the current landscape.',
            'image' => 'uploads/images/sample2.jpg',
            'tags' => 'funding, growth, strategy',
            'newskeywords' => 'funding, growth, strategy',
            'totalComment' => 23,
            'totalVotes' => 2,
            'views' => 15,
            'status' => 'D',
            'seoTitle' => 'Top Investment Strategies for 2025',
            'seoKeywords' => 'funding, growth, strategy',
            'seoDescription' => '10.0.0.55',
            'news_date' => '2025-02-02 10:30:00'
        ]);

        DB::table('news')->insert([
            'newsID' => 3,
            'homeTitle' => 'How to Scale Your Startup',
            'title' => 'How to Scale Your Startup',
            'shortDesc' => 'Third sample description.',
            'content' => 'Detailed analysis covering key aspects of business operations, market competition, and potential areas for expansion.',
            'image' => 'uploads/images/sample3.jpg',
            'tags' => 'technology, innovation, market',
            'newskeywords' => 'technology, innovation, market',
            'totalComment' => 24,
            'totalVotes' => 3,
            'views' => 25,
            'status' => 'A',
            'seoTitle' => 'How to Scale Your Startup',
            'seoKeywords' => 'technology, innovation, market',
            'seoDescription' => '172.16.0.100',
            'news_date' => '2025-03-03 10:30:00'
        ]);

    }
}