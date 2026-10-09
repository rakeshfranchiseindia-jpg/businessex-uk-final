<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profile_proposals', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('conversation_id');
            $table->string('profile_type', 30);
            $table->unsignedBigInteger('profile_id');
            $table->unsignedInteger('sender_user_id');
            $table->unsignedInteger('recipient_user_id');
            $table->string('title', 255);
            $table->text('description');
            $table->decimal('amount', 16, 2)->nullable();
            $table->string('status', 20)->default('pending');
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->index(['sender_user_id', 'status', 'created_at']);
            $table->index(['recipient_user_id', 'status', 'created_at']);
            $table->index(['conversation_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_proposals');
    }
};
