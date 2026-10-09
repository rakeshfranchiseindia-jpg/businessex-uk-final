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
        Schema::create('profile_startup_fund_raising', function (Blueprint $table) {
            $table->integer('startup_fund_id')->autoIncrement();
            $table->integer('startup_profile_id')->nullable(false);
            $table->integer('user_id')->nullable(false);
            $table->integer('fund_stage')->nullable(false);
            $table->string('fund_amount', 255)->nullable(false);
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
        Schema::dropIfExists('profile_startup_fund_raising');
    }
};