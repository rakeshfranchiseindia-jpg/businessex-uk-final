<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FiInvestorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('fi_investors')->insert([
            'fi_inv_id' => 1,
            'name' => 'Rajesh Kumar',
            'email' => 'admin@businessex.com'
        ]);

        DB::table('fi_investors')->insert([
            'fi_inv_id' => 2,
            'name' => 'Priya Sharma',
            'email' => 'john.doe@example.com'
        ]);

        DB::table('fi_investors')->insert([
            'fi_inv_id' => 3,
            'name' => 'Amit Patel',
            'email' => 'priya.sharma@example.com'
        ]);

    }
}