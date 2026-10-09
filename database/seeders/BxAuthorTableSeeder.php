<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BxAuthorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bx_author')->insert([
            'author_id' => 1,
            'author_name' => 'Rajesh Kumar',
            'author_email' => 'admin@businessex.com',
            'author_desig' => 'CEO',
            'author_dept' => 'Editorial',
            'is_active' => 0
        ]);

        DB::table('bx_author')->insert([
            'author_id' => 2,
            'author_name' => 'Priya Sharma',
            'author_email' => 'john.doe@example.com',
            'author_desig' => 'CTO',
            'author_dept' => 'Research',
            'is_active' => 1
        ]);

        DB::table('bx_author')->insert([
            'author_id' => 3,
            'author_name' => 'Amit Patel',
            'author_email' => 'priya.sharma@example.com',
            'author_desig' => 'Managing Director',
            'author_dept' => 'Content',
            'is_active' => 0
        ]);

    }
}