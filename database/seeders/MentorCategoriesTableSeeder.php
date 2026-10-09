<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MentorCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mentor_categories')->insert([
            'mentor_category_id' => 1,
            'mentor_category_name' => 'Sample string data',
            'category_slug' => 'sample-slug-1',
            'mentor_parent_id' => 1,
            'mentor_category_status' => 0
        ]);

        DB::table('mentor_categories')->insert([
            'mentor_category_id' => 2,
            'mentor_category_name' => 'Another sample entry',
            'category_slug' => 'sample-slug-2',
            'mentor_parent_id' => 2,
            'mentor_category_status' => 1
        ]);

        DB::table('mentor_categories')->insert([
            'mentor_category_id' => 3,
            'mentor_category_name' => 'Third sample value',
            'category_slug' => 'sample-slug-3',
            'mentor_parent_id' => 3,
            'mentor_category_status' => 0
        ]);

    }
}