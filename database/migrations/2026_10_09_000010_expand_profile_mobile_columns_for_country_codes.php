<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profile_business', function (Blueprint $table): void {
            $table->string('seller_mobile', 20)->nullable()->change();
        });

        Schema::table('profile_startups', function (Blueprint $table): void {
            $table->string('startup_mobile', 20)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('profile_business', function (Blueprint $table): void {
            $table->string('seller_mobile', 12)->nullable()->change();
        });

        Schema::table('profile_startups', function (Blueprint $table): void {
            $table->string('startup_mobile', 12)->nullable()->change();
        });
    }
};
