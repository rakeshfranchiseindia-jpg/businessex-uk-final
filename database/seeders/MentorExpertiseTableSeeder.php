<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MentorExpertiseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mentor_expertise')->insert([
            'mentor_expert_id' => 1,
            'mentor_id' => 1,
            'user_id' => 1,
            'exp_years' => 2,
            'exp_industry' => 1
        ]);

        DB::table('mentor_expertise')->insert([
            'mentor_expert_id' => 2,
            'mentor_id' => 2,
            'user_id' => 2,
            'exp_years' => 3,
            'exp_industry' => 2
        ]);

        DB::table('mentor_expertise')->insert([
            'mentor_expert_id' => 3,
            'mentor_id' => 3,
            'user_id' => 3,
            'exp_years' => 4,
            'exp_industry' => 3
        ]);

    }
}