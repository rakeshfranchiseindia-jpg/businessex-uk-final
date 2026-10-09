<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profile_instant_responses', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->primary();
            $table->boolean('enabled')->default(false);
            $table->string('message', 500);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_instant_responses');
    }
};
