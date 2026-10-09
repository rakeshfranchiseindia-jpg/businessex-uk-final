<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BxCitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bx_cities')->insert([
            'id' => 1,
            'city' => 'Mumbai',
            'state' => 'Maharashtra'
        ]);

        DB::table('bx_cities')->insert([
            'id' => 2,
            'city' => 'Delhi',
            'state' => 'Delhi'
        ]);

        DB::table('bx_cities')->insert([
            'id' => 3,
            'city' => 'Bangalore',
            'state' => 'Karnataka'
        ]);

    }
}