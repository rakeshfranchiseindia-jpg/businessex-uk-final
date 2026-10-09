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
        Schema::create('loc_pref_broker', function (Blueprint $table) {
            $table->integer('broker_loc_id')->autoIncrement();
            $table->integer('broker_profile_id')->nullable(false);
            $table->integer('user_id')->nullable(false);
            $table->string('location_name', 255)->nullable(false);
            $table->string('loc_state', 255)->nullable(false);
            $table->string('loc_country', 255)->nullable(false);
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
        Schema::dropIfExists('loc_pref_broker');
    }
};