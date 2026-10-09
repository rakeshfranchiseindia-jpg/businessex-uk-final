<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_knowledge_base', function (Blueprint $table): void {
            $table->id();
            $table->string('intent', 100)->unique();
            $table->string('keywords', 1000);
            $table->text('answer');
            $table->string('answer_url', 500)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('chatbot_leads', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 255);
            $table->string('email', 255);
            $table->string('mobile', 32);
            $table->text('message');
            $table->timestamps();

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_leads');
        Schema::dropIfExists('chatbot_knowledge_base');
    }
};
