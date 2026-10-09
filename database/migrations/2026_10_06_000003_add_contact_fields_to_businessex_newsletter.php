<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('businessex_newsletter', function (Blueprint $table): void {
            $table->string('name')->nullable();
            $table->string('phone', 32)->nullable();
            $table->string('city', 100)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('businessex_newsletter', function (Blueprint $table): void {
            $table->dropColumn(['name', 'phone', 'city']);
        });
    }
};
