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
        Schema::create('profile_mentor_prof_exp', function (Blueprint $table) {
            $table->integer('mentor_mgmt_id')->autoIncrement();
            $table->integer('mentor_profile_id')->nullable(false);
            $table->integer('user_id')->nullable(false);
            $table->integer('exp_year')->nullable(false);
            $table->integer('exp_sector')->nullable(false);
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
        Schema::dropIfExists('profile_mentor_prof_exp');
    }
};