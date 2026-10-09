<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('country')->insert([
            [
                'country_id'   => 1,
                'country_name' => 'India',
                'country_code' => '+91',
            ],
            [
                'country_id'   => 2,
                'country_name' => 'United States',
                'country_code' => '+1',
            ],
            [
                'country_id'   => 3,
                'country_name' => 'Brazil',
                'country_code' => '+55',
            ],
            [
                'country_id'   => 4,
                'country_name' => 'Japan',
                'country_code' => '+81',
            ],
            [
                'country_id'   => 5,
                'country_name' => 'United Kingdom',
                'country_code' => '+44',
            ],
        ]);
    }
}
