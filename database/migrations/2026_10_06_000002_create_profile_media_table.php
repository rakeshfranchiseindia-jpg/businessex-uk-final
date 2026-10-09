<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profile_media', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('profile_type', 32);
            $table->unsignedInteger('profile_id');
            $table->string('field_name', 100);
            $table->unsignedSmallInteger('file_index')->default(0);
            $table->string('media_type', 20);
            $table->string('disk', 32)->default('s3');
            $table->string('object_key', 1024);
            $table->longText('url')->nullable();
            $table->string('original_name', 255);
            $table->string('mime_type', 255);
            $table->unsignedBigInteger('file_size');
            $table->boolean('is_public')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'profile_type', 'profile_id'], 'profile_media_owner_index');
            $table->index(['profile_type', 'profile_id'], 'profile_media_profile_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_media');
    }
};
