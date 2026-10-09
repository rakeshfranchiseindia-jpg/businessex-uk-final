<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChatbotKnowledgeBaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $entries = [
            [
                'intent' => 'browse-businesses',
                'keywords' => ['businesses', 'business for sale', 'buy a business', 'listings', 'opportunities'],
                'answer' => 'Browse current business opportunities on our Business Listings page.',
                'url' => '/business-listing',
            ],
            [
                'intent' => 'find-investors',
                'keywords' => ['investor', 'investors', 'funding', 'investment', 'raise capital'],
                'answer' => 'Explore investor profiles and funding opportunities on our Investor Listings page.',
                'url' => '/investor-listing',
            ],
            [
                'intent' => 'find-startups',
                'keywords' => ['startup', 'startups', 'founder', 'new venture'],
                'answer' => 'Discover startups looking for partners and investment on our Startup Listings page.',
                'url' => '/startup-listing',
            ],
            [
                'intent' => 'find-mentors',
                'keywords' => ['mentor', 'mentors', 'mentorship', 'advice', 'expert'],
                'answer' => 'Find experienced business mentors on our Mentor Listings page.',
                'url' => '/mentor-listing',
            ],
            [
                'intent' => 'create-profile',
                'keywords' => ['register', 'registration', 'sign up', 'create profile', 'join', 'account'],
                'answer' => 'You can register and create a BusinessX profile from our registration page.',
                'url' => '/registration',
            ],
            [
                'intent' => 'contact-member',
                'keywords' => ['contact business', 'message business', 'contact profile', 'send message'],
                'answer' => 'Open a profile listing and choose Contact to send its owner a private message. Replies appear in your dashboard inbox.',
                'url' => null,
            ],
            [
                'intent' => 'pricing',
                'keywords' => ['price', 'pricing', 'cost', 'membership', 'plans', 'fee'],
                'answer' => 'See current membership information on our Pricing page.',
                'url' => '/pricing',
            ],
            [
                'intent' => 'support',
                'keywords' => ['support', 'help', 'speak to someone', 'contact us', 'customer service'],
                'answer' => 'Leave your details here and our team can follow up, or visit our Contact page.',
                'url' => '/contact-us',
            ],
        ];

        foreach ($entries as $entry) {
            DB::table('chatbot_knowledge_base')->updateOrInsert(
                ['intent' => $entry['intent']],
                [
                    'keywords' => json_encode($entry['keywords'], JSON_THROW_ON_ERROR),
                    'answer' => $entry['answer'],
                    'answer_url' => $entry['url'],
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
