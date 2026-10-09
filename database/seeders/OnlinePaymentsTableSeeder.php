<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OnlinePaymentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('online_payments')->insert([
            'payment_id' => 1,
            'order_no' => 'ORD2000',
            'user_id' => 1,
            'profile_type' => 1,
            'profile_id' => 1,
            'coupon_id' => 1,
            'name' => 'Rajesh Kumar',
            'email' => 'admin@businessex.com',
            'phone' => '9876543210',
            'city' => 'Mumbai',
            'country' => 'India',
            'product_details' => 'Sample description text.',
            'membership_plan' => '192.168.1.1',
            'amount' => '5000000',
            'udf' => 'product_info_placeholder',
            'payment_status' => 0,
            'payment_mode' => 'online',
            'status_message' => 0
        ]);

        DB::table('online_payments')->insert([
            'payment_id' => 2,
            'order_no' => 'ORD2001',
            'user_id' => 2,
            'profile_type' => 2,
            'profile_id' => 2,
            'coupon_id' => 2,
            'name' => 'Priya Sharma',
            'email' => 'john.doe@example.com',
            'phone' => '8765432109',
            'city' => 'Delhi',
            'country' => 'United States',
            'product_details' => 'Another sample description.',
            'membership_plan' => '10.0.0.55',
            'amount' => '10000000',
            'udf' => 'product_info_placeholder',
            'payment_status' => 1,
            'payment_mode' => 'offline',
            'addon_one' => 1,
            'addon_two' => 1,
            'addon_three' => 1,
            'addon_four' => 1,
            'addon_five' => 1,
            'addon_six' => 1,
            'status_message' => 1
        ]);

        DB::table('online_payments')->insert([
            'payment_id' => 3,
            'order_no' => 'ORD2002',
            'user_id' => 3,
            'profile_type' => 3,
            'profile_id' => 3,
            'coupon_id' => 3,
            'name' => 'Amit Patel',
            'email' => 'priya.sharma@example.com',
            'phone' => '7654321098',
            'city' => 'Bangalore',
            'country' => 'Singapore',
            'product_details' => 'Third sample description.',
            'membership_plan' => '172.16.0.100',
            'amount' => '25000000',
            'udf' => 'product_info_placeholder',
            'payment_status' => 0,
            'payment_mode' => 'upi',
            'status_message' => 0
        ]);

    }
}