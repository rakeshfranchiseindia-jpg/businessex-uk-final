<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->integer('user_prof_id')->autoIncrement();
            $table->integer('user_id')->nullable(false);
            $table->integer('profile_id')->nullable(false);
            $table->tinyInteger('profile_type')->nullable(false);
            $table->string('profile_str', 20)->nullable(false);
            $table->tinyInteger('profile_status')->nullable(false);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
};