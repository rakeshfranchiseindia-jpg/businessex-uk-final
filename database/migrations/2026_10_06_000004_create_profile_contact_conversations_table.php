<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profile_contact_conversations', function (Blueprint $table): void {
            $table->id();
            $table->string('profile_type', 30);
            $table->unsignedBigInteger('profile_id');
            $table->unsignedInteger('owner_user_id');
            $table->unsignedInteger('sender_user_id');
            $table->string('sender_name', 255);
            $table->string('sender_email', 255);
            $table->string('sender_phone', 30)->nullable();
            $table->timestamps();

            $table->index(['owner_user_id', 'updated_at']);
            $table->index(['sender_user_id', 'updated_at']);
            $table->index(['profile_type', 'profile_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_contact_conversations');
    }
};
