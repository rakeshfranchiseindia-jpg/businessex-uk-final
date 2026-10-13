<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Article page: approved comments for one article, newest first.
        Schema::table('articles_comments', function (Blueprint $table): void {
            $table->index(['article_id', 'comment_status', 'created_at'], 'ac_article_status_created_idx');
        });

        // Dashboard: view counts grouped per profile. One row is inserted per
        // profile view, so this table grows without bound.
        Schema::table('profile_visitors', function (Blueprint $table): void {
            $table->index(['profile_type', 'profile_id'], 'pv_profile_idx');
        });
    }

    public function down(): void
    {
        Schema::table('profile_visitors', function (Blueprint $table): void {
            $table->dropIndex('pv_profile_idx');
        });

        Schema::table('articles_comments', function (Blueprint $table): void {
            $table->dropIndex('ac_article_status_created_idx');
        });
    }
};
