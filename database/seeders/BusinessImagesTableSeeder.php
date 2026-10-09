<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('business_images')->insert([
            'business_image_id' => 1,
            'business_id' => 1,
            'type' => 1,
            'business_img_path' => 'uploads/images/sample1.jpg',
            'business_img_name' => 'uploads/images/sample1.jpg',
            'is_active' => 0
        ]);

        DB::table('business_images')->insert([
            'business_image_id' => 2,
            'business_id' => 2,
            'type' => 2,
            'business_img_path' => 'uploads/images/sample2.jpg',
            'business_img_name' => 'uploads/images/sample2.jpg',
            'is_active' => 1
        ]);

        DB::table('business_images')->insert([
            'business_image_id' => 3,
            'business_id' => 3,
            'type' => 3,
            'business_img_path' => 'uploads/images/sample3.jpg',
            'business_img_name' => 'uploads/images/sample3.jpg',
            'is_active' => 0
        ]);

    }
}