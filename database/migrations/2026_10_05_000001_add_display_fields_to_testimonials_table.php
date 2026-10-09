<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table): void {
            $table->unsignedTinyInteger('rating')->default(5);
            $table->string('image_path', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table): void {
            $table->dropIndex(['is_active', 'sort_order']);
            $table->dropColumn(['rating', 'image_path', 'is_active', 'sort_order']);
        });
    }
};
