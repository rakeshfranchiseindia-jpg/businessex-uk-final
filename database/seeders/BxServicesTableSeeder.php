<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BxServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bx_services')->insert([
            'payment_id' => 1,
            'order_no' => 'ORD2000',
            'user_id' => 1,
            'name' => 'Rajesh Kumar',
            'email' => 'admin@businessex.com',
            'phone' => '9876543210',
            'company' => 'Sample string data',
            'designation' => 'CEO',
            'event_city' => 'Mumbai',
            'event_date' => '2025-01-01',
            'event_timing' => '10:00 AM - 01:00 PM',
            'event_topic' => 'uploads/images/sample1.jpg',
            'is_member' => 0,
            'amount' => '5000000',
            'service_type' => 1,
            'product_details' => 'Sample description text.',
            'udf' => 'product_info_placeholder',
            'payment_status' => 0,
            'payment_mode' => 'online',
            'contact_response' => 5,
            'contact_status' => 0
        ]);

        DB::table('bx_services')->insert([
            'payment_id' => 2,
            'order_no' => 'ORD2001',
            'user_id' => 2,
            'name' => 'Priya Sharma',
            'email' => 'john.doe@example.com',
            'phone' => '8765432109',
            'company' => 'Another sample entry',
            'designation' => 'CTO',
            'event_city' => 'Delhi',
            'event_date' => '2025-02-02',
            'event_timing' => '02:00 PM - 05:00 PM',
            'event_topic' => 'uploads/images/sample2.jpg',
            'is_member' => 1,
            'amount' => '10000000',
            'service_type' => 2,
            'product_details' => 'Another sample description.',
            'udf' => 'product_info_placeholder',
            'payment_status' => 1,
            'payment_mode' => 'offline',
            'contact_response' => 15,
            'contact_status' => 1
        ]);

        DB::table('bx_services')->insert([
            'payment_id' => 3,
            'order_no' => 'ORD2002',
            'user_id' => 3,
            'name' => 'Amit Patel',
            'email' => 'priya.sharma@example.com',
            'phone' => '7654321098',
            'company' => 'Third sample value',
            'designation' => 'Managing Director',
            'event_city' => 'Bangalore',
            'event_date' => '2025-03-03',
            'event_timing' => '11:00 AM - 12:30 PM',
            'event_topic' => 'uploads/images/sample3.jpg',
            'is_member' => 0,
            'amount' => '25000000',
            'service_type' => 3,
            'product_details' => 'Third sample description.',
            'udf' => 'product_info_placeholder',
            'payment_status' => 0,
            'payment_mode' => 'upi',
            'contact_response' => 25,
            'contact_status' => 0
        ]);

    }
}