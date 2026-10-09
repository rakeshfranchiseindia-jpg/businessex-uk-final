<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StartupImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('startup_images')->insert([
            'startup_image_id' => 1,
            'startup_id' => 1,
            'type' => 1,
            'startup_img_path' => 'uploads/images/sample1.jpg',
            'startup_img_name' => 'uploads/images/sample1.jpg',
            'is_active' => 0
        ]);

        DB::table('startup_images')->insert([
            'startup_image_id' => 2,
            'startup_id' => 2,
            'type' => 2,
            'startup_img_path' => 'uploads/images/sample2.jpg',
            'startup_img_name' => 'uploads/images/sample2.jpg',
            'is_active' => 1
        ]);

        DB::table('startup_images')->insert([
            'startup_image_id' => 3,
            'startup_id' => 3,
            'type' => 3,
            'startup_img_path' => 'uploads/images/sample3.jpg',
            'startup_img_name' => 'uploads/images/sample3.jpg',
            'is_active' => 0
        ]);

    }
}