<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profile_memberships', function (Blueprint $table): void {
            // Null = the profile owner paid for this themselves (online_payments);
            // set = admin_user.admin_id of the admin who granted the plan manually.
            $table->unsignedInteger('assigned_by')->nullable()->after('upg_source');
        });
    }

    public function down(): void
    {
        Schema::table('profile_memberships', function (Blueprint $table): void {
            $table->dropColumn('assigned_by');
        });
    }
};
