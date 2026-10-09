<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConversationReplyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('conversation_reply')->insert([
            'id' => 1,
            'reply' => 'This is a sample description providing enough detail to be realistic for testing and development purposes.',
            'from_id' => 1,
            'to_id' => 1,
            'timestamp' => date('Y-m-d H:i:s', 1720000000),
            'request_id' => 1,
            'readstatus' => '1'
        ]);

        DB::table('conversation_reply')->insert([
            'id' => 2,
            'reply' => 'A comprehensive overview of business strategies, growth opportunities, and market trends in the current landscape.',
            'from_id' => 2,
            'to_id' => 2,
            'timestamp' => date('Y-m-d H:i:s', 1720000000),
            'request_id' => 2,
            'readstatus' => '2'
        ]);

        DB::table('conversation_reply')->insert([
            'id' => 3,
            'reply' => 'Detailed analysis covering key aspects of business operations, market competition, and potential areas for expansion.',
            'from_id' => 3,
            'to_id' => 3,
            'timestamp' => date('Y-m-d H:i:s', 1720000000),
            'request_id' => 3,
            'readstatus' => '1'
        ]);

    }
}