<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profile_contact_conversation_reads', function (Blueprint $table): void {
            $table->unsignedBigInteger('conversation_id');
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('last_read_message_id');
            $table->timestamps();

            $table->primary(['conversation_id', 'user_id']);
            $table->index(['user_id', 'last_read_message_id'], 'pc_conversation_reads_user_msg_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_contact_conversation_reads');
    }
};
