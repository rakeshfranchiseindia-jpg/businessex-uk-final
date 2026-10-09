<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocPrefBrokerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('loc_pref_broker')->insert([
            'broker_loc_id' => 1,
            'broker_profile_id' => 1,
            'user_id' => 1,
            'location_name' => '123 Business Park, Andheri East',
            'loc_state' => 'Maharashtra',
            'loc_country' => 'India',
            'profile_status' => 0
        ]);

        DB::table('loc_pref_broker')->insert([
            'broker_loc_id' => 2,
            'broker_profile_id' => 2,
            'user_id' => 2,
            'location_name' => '456 Tech Hub, Whitefield',
            'loc_state' => 'Delhi',
            'loc_country' => 'United States',
            'profile_status' => 1
        ]);

        DB::table('loc_pref_broker')->insert([
            'broker_loc_id' => 3,
            'broker_profile_id' => 3,
            'user_id' => 3,
            'location_name' => '789 Corporate Tower, Connaught Place',
            'loc_state' => 'Karnataka',
            'loc_country' => 'Singapore',
            'profile_status' => 0
        ]);

    }
}