<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BexShortsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bex_shorts')->insert([
            'bex_id' => 1,
            'bex_title' => 'Getting Started with Business Growth',
            'bex_description' => '192.168.1.1',
            'author_id' => 1,
            'image_path' => 'uploads/images/sample1.jpg',
            'reference_page_name' => 1,
            'reference_page_link' => 'https://example.com',
            'associated_tag' => 'business, startup, investment',
            'seo_title' => 'Getting Started with Business Growth',
            'seo_keywords' => 'business, startup, investment',
            'seo_desc' => 'Sample description text.',
            'status' => 0,
            'created_by' => 1
        ]);

        DB::table('bex_shorts')->insert([
            'bex_id' => 2,
            'bex_title' => 'Top Investment Strategies for 2025',
            'bex_description' => '10.0.0.55',
            'author_id' => 2,
            'image_path' => 'uploads/images/sample2.jpg',
            'reference_page_name' => 2,
            'reference_page_link' => 'https://linkedin.com/in/example',
            'associated_tag' => 'funding, growth, strategy',
            'seo_title' => 'Top Investment Strategies for 2025',
            'seo_keywords' => 'funding, growth, strategy',
            'seo_desc' => 'Another sample description.',
            'status' => 1,
            'created_by' => 2
        ]);

        DB::table('bex_shorts')->insert([
            'bex_id' => 3,
            'bex_title' => 'How to Scale Your Startup',
            'bex_description' => '172.16.0.100',
            'author_id' => 3,
            'image_path' => 'uploads/images/sample3.jpg',
            'reference_page_name' => 3,
            'reference_page_link' => 'https://company.com',
            'associated_tag' => 'technology, innovation, market',
            'seo_title' => 'How to Scale Your Startup',
            'seo_keywords' => 'technology, innovation, market',
            'seo_desc' => 'Third sample description.',
            'status' => 0,
            'created_by' => 3
        ]);

    }
}