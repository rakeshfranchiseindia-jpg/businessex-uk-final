<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $testimonials = [
            [
                'text' => 'BusinessX helped us connect with serious buyers and gave us clear guidance throughout the process.',
                'name' => 'Mark Thompson',
                'designation' => 'Business owner, Manchester',
                'rating' => 5,
                'image_path' => 'assets/img/default-business-profile.png',
                'sort_order' => 1,
            ],
            [
                'text' => 'The detailed opportunity profiles make it much easier to review businesses and decide which conversations to pursue.',
                'name' => 'Priya Sharma',
                'designation' => 'Investor, London',
                'rating' => 5,
                'image_path' => 'assets/img/default-investor-profile.png',
                'sort_order' => 2,
            ],
            [
                'text' => 'I found practical advice and a supportive network that helped me move my startup plans forward.',
                'name' => 'Elaine Foster',
                'designation' => 'Startup mentor, Leeds',
                'rating' => 5,
                'image_path' => 'assets/img/default-mentor-profile.png',
                'sort_order' => 3,
            ],
            [
                'text' => 'The platform made it straightforward to introduce our company to potential partners and investors.',
                'name' => 'James Wilson',
                'designation' => 'Founder, Bristol',
                'rating' => 4,
                'image_path' => 'assets/img/default-business-profile.png',
                'sort_order' => 4,
            ],
            [
                'text' => 'Helpful support and a focused business community made BusinessX a valuable place to explore new opportunities.',
                'name' => 'Sofia Patel',
                'designation' => 'Entrepreneur, Edinburgh',
                'rating' => 5,
                'image_path' => 'assets/img/default-business-profile.png',
                'sort_order' => 5,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            DB::table('testimonials')->updateOrInsert(
                ['name' => $testimonial['name']],
                array_merge($testimonial, [
                    'is_active' => true,
                    'updated_at' => $now,
                    'created_at' => $now,
                ])
            );
        }
    }
}
