<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('chatbot_knowledge_base', 'answer_url')) {
            Schema::table('chatbot_knowledge_base', function (Blueprint $table): void {
                $table->string('answer_url', 500)->nullable()->after('answer');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('chatbot_knowledge_base', 'answer_url')) {
            Schema::table('chatbot_knowledge_base', function (Blueprint $table): void {
                $table->dropColumn('answer_url');
            });
        }
    }
};
