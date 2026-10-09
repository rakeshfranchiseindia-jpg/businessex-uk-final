<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('seo')->insert([
            'id' => 1,
            'profile_type' => 1,
            'cat_id' => 1,
            'title' => 'Getting Started with Business Growth',
            'keyword' => 'business, startup, investment',
            'description' => '192.168.1.1',
            'meta_description' => '192.168.1.1'
        ]);

        DB::table('seo')->insert([
            'id' => 2,
            'profile_type' => 2,
            'cat_id' => 2,
            'title' => 'Top Investment Strategies for 2025',
            'keyword' => 'funding, growth, strategy',
            'description' => '10.0.0.55',
            'meta_description' => '10.0.0.55'
        ]);

        DB::table('seo')->insert([
            'id' => 3,
            'profile_type' => 3,
            'cat_id' => 3,
            'title' => 'How to Scale Your Startup',
            'keyword' => 'technology, innovation, market',
            'description' => '172.16.0.100',
            'meta_description' => '172.16.0.100'
        ]);

    }
}