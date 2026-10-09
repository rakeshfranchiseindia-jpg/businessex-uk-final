<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleNewsImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('article_news_images')->insert([
            'id' => 1,
            'content_id' => 1,
            'type' => 1,
            'img_path' => 'uploads/images/sample1.jpg',
            'is_active' => 0
        ]);

        DB::table('article_news_images')->insert([
            'id' => 2,
            'content_id' => 2,
            'type' => 2,
            'img_path' => 'uploads/images/sample2.jpg',
            'is_active' => 1
        ]);

        DB::table('article_news_images')->insert([
            'id' => 3,
            'content_id' => 3,
            'type' => 3,
            'img_path' => 'uploads/images/sample3.jpg',
            'is_active' => 0
        ]);

    }
}