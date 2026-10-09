<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BxIndustryreportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bx_industryreports')->insert([
            'industryreport_id' => 1,
            'industryreport_title' => 'Getting Started with Business Growth',
            'industryreport_home_title' => 'Getting Started with Business Growth',
            'industry_sector' => 1,
            'short_desc' => 'This is a sample description providing enough detail to be realistic for testing and development purposes.',
            'industryreport_content' => 'This is a sample description providing enough detail to be realistic for testing and development purposes.',
            'author_id' => 1,
            'image_path' => 'uploads/images/sample1.jpg',
            'listing_image_path' => 'uploads/images/sample1.jpg',
            'industryreport_pdf_path' => 'uploads/images/sample1.jpg',
            'industryreport_tags' => 'business, startup, investment',
            'industryreport_status' => 0,
            'seo_title' => 'Getting Started with Business Growth',
            'seo_keywords' => 'business, startup, investment',
            'seo_desc' => 'Sample description text.',
            'industryreport_views' => 5,
            'industryreport_comments' => 0,
            'created_by' => 1
        ]);

        DB::table('bx_industryreports')->insert([
            'industryreport_id' => 2,
            'industryreport_title' => 'Top Investment Strategies for 2025',
            'industryreport_home_title' => 'Top Investment Strategies for 2025',
            'industry_sector' => 2,
            'short_desc' => 'A comprehensive overview of business strategies, growth opportunities, and market trends in the current landscape.',
            'industryreport_content' => 'A comprehensive overview of business strategies, growth opportunities, and market trends in the current landscape.',
            'author_id' => 2,
            'image_path' => 'uploads/images/sample2.jpg',
            'listing_image_path' => 'uploads/images/sample2.jpg',
            'industryreport_pdf_path' => 'uploads/images/sample2.jpg',
            'industryreport_tags' => 'funding, growth, strategy',
            'industryreport_status' => 1,
            'seo_title' => 'Top Investment Strategies for 2025',
            'seo_keywords' => 'funding, growth, strategy',
            'seo_desc' => 'Another sample description.',
            'industryreport_views' => 15,
            'industryreport_comments' => 2,
            'created_by' => 2
        ]);

        DB::table('bx_industryreports')->insert([
            'industryreport_id' => 3,
            'industryreport_title' => 'How to Scale Your Startup',
            'industryreport_home_title' => 'How to Scale Your Startup',
            'industry_sector' => 3,
            'short_desc' => 'Detailed analysis covering key aspects of business operations, market competition, and potential areas for expansion.',
            'industryreport_content' => 'Detailed analysis covering key aspects of business operations, market competition, and potential areas for expansion.',
            'author_id' => 3,
            'image_path' => 'uploads/images/sample3.jpg',
            'listing_image_path' => 'uploads/images/sample3.jpg',
            'industryreport_pdf_path' => 'uploads/images/sample3.jpg',
            'industryreport_tags' => 'technology, innovation, market',
            'industryreport_status' => 0,
            'seo_title' => 'How to Scale Your Startup',
            'seo_keywords' => 'technology, innovation, market',
            'seo_desc' => 'Third sample description.',
            'industryreport_views' => 25,
            'industryreport_comments' => 0,
            'created_by' => 3
        ]);

    }
}