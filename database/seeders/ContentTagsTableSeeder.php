<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentTagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('content_tags')->insert([
            'tag_id' => 1,
            'tag_name' => 'business, startup, investment',
            'tag_slug' => 'sample-slug-1',
            'tag_status' => 0
        ]);

        DB::table('content_tags')->insert([
            'tag_id' => 2,
            'tag_name' => 'funding, growth, strategy',
            'tag_slug' => 'sample-slug-2',
            'tag_status' => 0
        ]);

        DB::table('content_tags')->insert([
            'tag_id' => 3,
            'tag_name' => 'technology, innovation, market',
            'tag_slug' => 'sample-slug-3',
            'tag_status' => 0
        ]);

    }
}