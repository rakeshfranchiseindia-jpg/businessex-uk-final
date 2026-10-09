<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profile_proposal_attachments', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('proposal_id');
            $table->unsignedInteger('uploaded_by_user_id');
            $table->string('disk', 32)->default('s3');
            $table->string('object_key', 1024);
            $table->string('original_name', 255);
            $table->string('mime_type', 255);
            $table->unsignedBigInteger('file_size');
            $table->timestamps();

            $table->index(['proposal_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_proposal_attachments');
    }
};
