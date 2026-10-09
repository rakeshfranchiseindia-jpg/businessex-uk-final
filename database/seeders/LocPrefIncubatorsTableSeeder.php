<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocPrefIncubatorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('loc_pref_incubators')->insert([
            'incubator_loc_id' => 1,
            'incubator_profile_id' => 1,
            'user_id' => 1,
            'place_id' => 1,
            'location_name' => '123 Business Park, Andheri East',
            'loc_state' => 'Maharashtra',
            'loc_country' => 'India',
            'loc_latitude' => '19.0760',
            'loc_longitude' => '72.8777',
            'profile_status' => 0
        ]);

        DB::table('loc_pref_incubators')->insert([
            'incubator_loc_id' => 2,
            'incubator_profile_id' => 2,
            'user_id' => 2,
            'place_id' => 2,
            'location_name' => '456 Tech Hub, Whitefield',
            'loc_state' => 'Delhi',
            'loc_country' => 'United States',
            'loc_latitude' => '28.7041',
            'loc_longitude' => '77.1025',
            'profile_status' => 1
        ]);

        DB::table('loc_pref_incubators')->insert([
            'incubator_loc_id' => 3,
            'incubator_profile_id' => 3,
            'user_id' => 3,
            'place_id' => 3,
            'location_name' => '789 Corporate Tower, Connaught Place',
            'loc_state' => 'Karnataka',
            'loc_country' => 'Singapore',
            'loc_latitude' => '12.9716',
            'loc_longitude' => '77.5946',
            'profile_status' => 0
        ]);

    }
}