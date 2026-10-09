<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profile_business', function (Blueprint $table): void {
            $table->index('user_id', 'pb_user_id_idx');
            $table->index(['business_profile_status', 'created_at', 'business_id'], 'pb_status_created_id_idx');
        });

        Schema::table('profile_investor', function (Blueprint $table): void {
            $table->index(['inv_profile_status', 'created_at', 'investor_id'], 'pi_status_created_id_idx');
        });

        Schema::table('profile_mentors', function (Blueprint $table): void {
            $table->index('user_id', 'pm_user_id_idx');
            $table->index(['mentor_profile_status', 'created_at', 'mentor_id'], 'pm_status_created_id_idx');
        });

        Schema::table('profile_startups', function (Blueprint $table): void {
            $table->index('user_id', 'ps_user_id_idx');
            $table->index(['startup_profile_status', 'created_at', 'startup_id'], 'ps_status_created_id_idx');
            $table->index(['startup_profile_status', 'industry_sector'], 'ps_status_industry_idx');
        });

        Schema::table('industry_categories', function (Blueprint $table): void {
            $table->index(['parent_id', 'category_status'], 'ic_parent_status_idx');
        });

        Schema::table('ind_pref_business', function (Blueprint $table): void {
            $table->index(
                ['business_profile_id', 'profile_status', 'sub_category_id'],
                'ipb_profile_status_subcat_idx'
            );
            $table->index(
                ['business_profile_id', 'profile_status', 'parent_category_id'],
                'ipb_profile_status_parent_idx'
            );
        });

        Schema::table('bx_articles', function (Blueprint $table): void {
            $table->index(['article_status', 'created_at', 'article_id'], 'ba_status_created_id_idx');
            $table->index(['article_status', 'article_views', 'article_id'], 'ba_status_views_id_idx');
            $table->index(['article_status', 'article_comments', 'article_id'], 'ba_status_comments_id_idx');
        });
    }

    public function down(): void
    {
        Schema::table('bx_articles', function (Blueprint $table): void {
            $table->dropIndex('ba_status_comments_id_idx');
            $table->dropIndex('ba_status_views_id_idx');
            $table->dropIndex('ba_status_created_id_idx');
        });

        Schema::table('ind_pref_business', function (Blueprint $table): void {
            $table->dropIndex('ipb_profile_status_parent_idx');
            $table->dropIndex('ipb_profile_status_subcat_idx');
        });

        Schema::table('industry_categories', function (Blueprint $table): void {
            $table->dropIndex('ic_parent_status_idx');
        });

        Schema::table('profile_startups', function (Blueprint $table): void {
            $table->dropIndex('ps_status_industry_idx');
            $table->dropIndex('ps_status_created_id_idx');
            $table->dropIndex('ps_user_id_idx');
        });

        Schema::table('profile_mentors', function (Blueprint $table): void {
            $table->dropIndex('pm_status_created_id_idx');
            $table->dropIndex('pm_user_id_idx');
        });

        Schema::table('profile_investor', function (Blueprint $table): void {
            $table->dropIndex('pi_status_created_id_idx');
        });

        Schema::table('profile_business', function (Blueprint $table): void {
            $table->dropIndex('pb_status_created_id_idx');
            $table->dropIndex('pb_user_id_idx');
        });
    }
};
