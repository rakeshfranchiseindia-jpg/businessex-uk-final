<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentTagsAssignedTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('content_tags_assigned')->insert([
            'assigned_id' => 1,
            'content_type' => 1,
            'content_id' => 2,
            'tag_id' => 1
        ]);

        DB::table('content_tags_assigned')->insert([
            'assigned_id' => 2,
            'content_type' => 2,
            'content_id' => 2,
            'tag_id' => 2
        ]);

        DB::table('content_tags_assigned')->insert([
            'assigned_id' => 3,
            'content_type' => 1,
            'content_id' => 3,
            'tag_id' => 2
        ]);

    }
}