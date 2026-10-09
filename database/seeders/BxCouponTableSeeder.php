<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BxCouponTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bx_coupon')->insert([
            'id' => 1,
            'coupon_code' => 'SAVE10',
            'discount_type' => 1,
            'discount_amount' => 100000,
            'user_type' => 1,
            'profile_type' => 'Featured',
            'membership' => 'Gold',
            'start_date' => '2025-01-01 10:30:00',
            'end_date' => '2025-01-01 10:30:00',
            'max_redemption' => 5,
            'redemption_number' => 5,
            'platform' => 1,
            'is_active' => 0
        ]);

        DB::table('bx_coupon')->insert([
            'id' => 2,
            'coupon_code' => 'WELCOME20',
            'discount_type' => 2,
            'discount_amount' => 150000,
            'user_type' => 2,
            'profile_type' => 'Trending',
            'membership' => 'Gold',
            'start_date' => '2025-02-02 10:30:00',
            'end_date' => '2025-02-02 10:30:00',
            'max_redemption' => 15,
            'redemption_number' => 15,
            'platform' => 2,
            'is_active' => 1
        ]);

        DB::table('bx_coupon')->insert([
            'id' => 3,
            'coupon_code' => 'FLAT500',
            'discount_type' => 3,
            'discount_amount' => 200000,
            'user_type' => 3,
            'profile_type' => 'Latest',
            'membership' => 'Gold',
            'start_date' => '2025-03-03 10:30:00',
            'end_date' => '2025-03-03 10:30:00',
            'max_redemption' => 25,
            'redemption_number' => 25,
            'platform' => 3,
            'is_active' => 0
        ]);

    }
}