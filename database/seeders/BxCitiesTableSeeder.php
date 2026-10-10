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
        $cities = [
            // England
            ['city' => 'London', 'state' => 'England'],
            ['city' => 'Birmingham', 'state' => 'England'],
            ['city' => 'Manchester', 'state' => 'England'],
            ['city' => 'Liverpool', 'state' => 'England'],
            ['city' => 'Leeds', 'state' => 'England'],
            ['city' => 'Sheffield', 'state' => 'England'],
            ['city' => 'Bradford', 'state' => 'England'],
            ['city' => 'Bristol', 'state' => 'England'],
            ['city' => 'Coventry', 'state' => 'England'],
            ['city' => 'Leicester', 'state' => 'England'],
            ['city' => 'Nottingham', 'state' => 'England'],
            ['city' => 'Newcastle upon Tyne', 'state' => 'England'],
            ['city' => 'Sunderland', 'state' => 'England'],
            ['city' => 'Wolverhampton', 'state' => 'England'],
            ['city' => 'Brighton and Hove', 'state' => 'England'],
            ['city' => 'Milton Keynes', 'state' => 'England'],
            ['city' => 'Doncaster', 'state' => 'England'],
            ['city' => 'York', 'state' => 'England'],
            ['city' => 'Durham', 'state' => 'England'],
            ['city' => 'Canterbury', 'state' => 'England'],
            ['city' => 'Chichester', 'state' => 'England'],
            ['city' => 'Winchester', 'state' => 'England'],
            ['city' => 'Bath', 'state' => 'England'],
            ['city' => 'Oxford', 'state' => 'England'],
            ['city' => 'Cambridge', 'state' => 'England'],
            // Scotland
            ['city' => 'Edinburgh', 'state' => 'Scotland'],
            ['city' => 'Glasgow', 'state' => 'Scotland'],
            ['city' => 'Aberdeen', 'state' => 'Scotland'],
            ['city' => 'Dundee', 'state' => 'Scotland'],
            ['city' => 'Inverness', 'state' => 'Scotland'],
            ['city' => 'Stirling', 'state' => 'Scotland'],
            ['city' => 'Perth', 'state' => 'Scotland'],
            ['city' => 'Dunfermline', 'state' => 'Scotland'],
            // Wales
            ['city' => 'Cardiff', 'state' => 'Wales'],
            ['city' => 'Swansea', 'state' => 'Wales'],
            ['city' => 'Newport', 'state' => 'Wales'],
            ['city' => 'St Asaph', 'state' => 'Wales'],
            ['city' => 'Bangor', 'state' => 'Wales'],
            ['city' => 'Wrexham', 'state' => 'Wales'],
            ['city' => 'St Davids', 'state' => 'Wales'],
            // Northern Ireland
            ['city' => 'Belfast', 'state' => 'N. Ireland'],
            ['city' => 'Derry/Londonderry', 'state' => 'N. Ireland'],
            ['city' => 'Lisburn', 'state' => 'N. Ireland'],
            ['city' => 'Newry', 'state' => 'N. Ireland'],
            ['city' => 'Armagh', 'state' => 'N. Ireland'],
        ];

        foreach ($cities as $city) {
            DB::table('bx_cities')->updateOrInsert(
                ['city' => $city['city']],
                ['state' => $city['state'], 'country' => 5]
            );
        }
    }
}
