<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles_comments', function (Blueprint $table): void {
            $table->string('comment_name', 255)->change();
            $table->string('comment_email', 255)->change();
        });
    }

    public function down(): void
    {
        Schema::table('articles_comments', function (Blueprint $table): void {
            $table->string('comment_name', 55)->change();
            $table->string('comment_email', 55)->change();
        });
    }
};
