<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BxDfpBannerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bx_dfp_banner')->insert([
            'id' => 1,
            'dfp_id' => 1,
            'dfp_slot' => 'dfp_slot_1',
            'page' => 1,
            'location' => 1,
            'width' => 300,
            'height' => 250,
            'is_active' => 0
        ]);

        DB::table('bx_dfp_banner')->insert([
            'id' => 2,
            'dfp_id' => 2,
            'dfp_slot' => 'dfp_slot_2',
            'page' => 2,
            'location' => 2,
            'width' => 350,
            'height' => 275,
            'is_active' => 1
        ]);

        DB::table('bx_dfp_banner')->insert([
            'id' => 3,
            'dfp_id' => 3,
            'dfp_slot' => 'dfp_slot_3',
            'page' => 3,
            'location' => 3,
            'width' => 400,
            'height' => 300,
            'is_active' => 0
        ]);

    }
}