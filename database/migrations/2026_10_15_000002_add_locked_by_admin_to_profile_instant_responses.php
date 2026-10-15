<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profile_instant_responses', function (Blueprint $table): void {
            // Set when an admin turns this off; blocks the member from
            // re-enabling it themselves until an admin turns it back on.
            $table->boolean('locked_by_admin')->default(false)->after('enabled');
        });
    }

    public function down(): void
    {
        Schema::table('profile_instant_responses', function (Blueprint $table): void {
            $table->dropColumn('locked_by_admin');
        });
    }
};
