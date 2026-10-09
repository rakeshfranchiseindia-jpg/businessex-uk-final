<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConversationReplyAttachmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('conversation_reply_attachment')->insert([
            'id' => 1,
            'reply_id' => 1,
            'file_name' => 'document.pdf',
            'file_size' => '2.5 MB',
            'file_path' => 'uploads/images/sample1.jpg',
            'is_active' => '1'
        ]);

        DB::table('conversation_reply_attachment')->insert([
            'id' => 2,
            'reply_id' => 2,
            'file_name' => 'report.xlsx',
            'file_size' => '1.8 MB',
            'file_path' => 'uploads/images/sample2.jpg',
            'is_active' => '2'
        ]);

        DB::table('conversation_reply_attachment')->insert([
            'id' => 3,
            'reply_id' => 2,
            'file_name' => 'presentation.pptx',
            'file_size' => '4.2 MB',
            'file_path' => 'uploads/images/sample3.jpg',
            'is_active' => '1'
        ]);

    }
}