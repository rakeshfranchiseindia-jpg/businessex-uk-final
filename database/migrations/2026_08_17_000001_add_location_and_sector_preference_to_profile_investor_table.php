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
        Schema::table('profile_investor', function (Blueprint $table) {
            if (!Schema::hasColumn('profile_investor', 'location_preference')) {
                $table->string('location_preference', 255)->nullable()->after('company_designation');
            }

            if (!Schema::hasColumn('profile_investor', 'sector_preference')) {
                $table->string('sector_preference', 255)->nullable()->after('location_preference');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profile_investor', function (Blueprint $table) {
            if (Schema::hasColumn('profile_investor', 'sector_preference')) {
                $table->dropColumn('sector_preference');
            }

            if (Schema::hasColumn('profile_investor', 'location_preference')) {
                $table->dropColumn('location_preference');
            }
        });
    }
};
