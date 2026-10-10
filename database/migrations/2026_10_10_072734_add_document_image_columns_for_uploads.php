<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('profile_investor', function (Blueprint $table): void {
            if (! Schema::hasColumn('profile_investor', 'inv_doc_path')) {
                $table->string('inv_doc_path')->nullable()->after('inv_profile_pic_path');
            }
        });

        Schema::table('profile_mentors', function (Blueprint $table): void {
            if (! Schema::hasColumn('profile_mentors', 'mentor_doc_path')) {
                $table->string('mentor_doc_path')->nullable()->after('mentor_profile_pic');
            }
        });

        Schema::table('profile_startups', function (Blueprint $table): void {
            if (! Schema::hasColumn('profile_startups', 'startup_doc_path1')) {
                $table->string('startup_doc_path1')->nullable()->after('startup_doc_path');
            }
            if (! Schema::hasColumn('profile_startups', 'startup_doc_path2')) {
                $table->string('startup_doc_path2')->nullable()->after('startup_doc_path1');
            }
            if (! Schema::hasColumn('profile_startups', 'startup_doc_path3')) {
                $table->string('startup_doc_path3')->nullable()->after('startup_doc_path2');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profile_investor', function (Blueprint $table): void {
            $table->dropColumn('inv_doc_path');
        });

        Schema::table('profile_mentors', function (Blueprint $table): void {
            $table->dropColumn('mentor_doc_path');
        });

        Schema::table('profile_startups', function (Blueprint $table): void {
            $table->dropColumn(['startup_doc_path1', 'startup_doc_path2', 'startup_doc_path3']);
        });
    }
};
