<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserAccountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_account')->insert([
            'user_rand_id'    => 1,
            'name'            => 'Rajesh Kumar',
            'email'           => 'admin@businessex.com',
            'password'        => Hash::make('secret123'), // bcrypt hash
            'mobile'          => '9876543210',
            'location'        => '123 Business Park, Andheri East',
            'timezone'        => 'Sample string data',
            'company_name'    => 'TechVista Solutions Pvt Ltd',
            'designation'     => 'CEO',
            'is_active'       => 0,
            'email_verified_at' => now(),
            'reg_source'      => 1,
            'reg_profile'     => 'business',
            'linkedin_id'     => 'asfjdssasfhdkj',
            'google_id'       => 1,
            'facebook_id'     => 'ksdfjskdf',
            'profile_pic'     => 'uploads/images/sample1.jpg',
            'remember_token'  => Str::random(60),
            'verify_token'    => Str::random(60),
            'contact_response'=> 5,
            'contact_status'  => 0,
            'last_notify_at'  => '2025-01-01 10:30:00',
            'last_login_at'   => '2025-01-01 10:30:00',
        ]);

        DB::table('user_account')->insert([
            'user_rand_id'    => 2,
            'name'            => 'Priya Sharma',
            'email'           => 'john.doe@example.com',
            'password'        => Hash::make('password456'),
            'mobile'          => '8765432109',
            'location'        => '456 Tech Hub, Whitefield',
            'timezone'        => 'Another sample entry',
            'company_name'    => 'GreenEarth Enterprises',
            'designation'     => 'CTO',
            'is_active'       => 1,
            'email_verified_at' => now(),
            'reg_source'      => 2,
            'reg_profile'     => 'investor',
            'linkedin_id'     => 'akjsfhkjafh',
            'google_id'       => 2,
            'facebook_id'     => 'jkasfshjhf',
            'profile_pic'     => 'uploads/images/sample2.jpg',
            'remember_token'  => Str::random(60),
            'verify_token'    => Str::random(60),
            'contact_response'=> 15,
            'contact_status'  => 1,
            'last_notify_at'  => '2025-02-02 10:30:00',
            'last_login_at'   => '2025-02-02 10:30:00',
        ]);

        DB::table('user_account')->insert([
            'user_rand_id'    => 3,
            'name'            => 'Amit Patel',
            'email'           => 'priya.sharma@example.com',
            'password'        => Hash::make('mypassword789'),
            'mobile'          => '7654321098',
            'location'        => '789 Corporate Tower, Connaught Place',
            'timezone'        => 'Third sample value',
            'company_name'    => 'SkyRocket Innovations',
            'designation'     => 'Managing Director',
            'is_active'       => 0,
            'email_verified_at' => now(),
            'reg_source'      => 3,
            'reg_profile'     => 'startup',
            'linkedin_id'     => 'https://company.com',
            'google_id'       => 3,
            'facebook_id'     => 'https://company.com',
            'profile_pic'     => 'uploads/images/sample3.jpg',
            'remember_token'  => Str::random(60),
            'verify_token'    => Str::random(60),
            'contact_response'=> 25,
            'contact_status'  => 0,
            'last_notify_at'  => '2025-03-03 10:30:00',
            'last_login_at'   => '2025-03-03 10:30:00',
        ]);
    }
}
