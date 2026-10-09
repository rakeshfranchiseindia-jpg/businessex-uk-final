<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsListTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('news_list')->insert([
            'news_id' => 1,
            'prev_id' => 1,
            'news_type' => 'Featured',
            'kicker' => 'Getting Started with Business Growth',
            'title' => 'Getting Started with Business Growth',
            'homeTitle' => 'Getting Started with Business Growth',
            'shortDesc' => 'Sample description text.',
            'content' => 'This is a sample description providing enough detail to be realistic for testing and development purposes.',
            'image' => 'uploads/images/sample1.jpg',
            'slug' => 'sample-slug-1',
            'related_brand' => 'TechCorp',
            'time' => '2025-01-01 10:30:00',
            'views' => 5,
            'totalComment' => 23,
            'totalVotes' => 1,
            'facebook_shared' => 3,
            'status' => 0
        ]);

        DB::table('news_list')->insert([
            'news_id' => 2,
            'prev_id' => 2,
            'news_type' => 'Trending',
            'kicker' => 'Top Investment Strategies for 2025',
            'title' => 'Top Investment Strategies for 2025',
            'homeTitle' => 'Top Investment Strategies for 2025',
            'shortDesc' => 'Another sample description.',
            'content' => 'A comprehensive overview of business strategies, growth opportunities, and market trends in the current landscape.',
            'image' => 'uploads/images/sample2.jpg',
            'slug' => 'sample-slug-2',
            'related_brand' => 'InnoVate',
            'time' => '2025-02-02 10:30:00',
            'views' => 15,
            'totalComment' => 24,
            'totalVotes' => 2,
            'facebook_shared' => 2,
            'status' => 1
        ]);

        DB::table('news_list')->insert([
            'news_id' => 3,
            'prev_id' => 3,
            'news_type' => 'Latest',
            'kicker' => 'How to Scale Your Startup',
            'title' => 'How to Scale Your Startup',
            'homeTitle' => 'How to Scale Your Startup',
            'shortDesc' => 'Third sample description.',
            'content' => 'Detailed analysis covering key aspects of business operations, market competition, and potential areas for expansion.',
            'image' => 'uploads/images/sample3.jpg',
            'slug' => 'sample-slug-3',
            'related_brand' => 'GrowEdge',
            'time' => '2025-03-03 10:30:00',
            'views' => 25,
            'totalComment' => 45,
            'totalVotes' => 3,
            'facebook_shared' => 1,
            'status' => 0
        ]);

    }
}