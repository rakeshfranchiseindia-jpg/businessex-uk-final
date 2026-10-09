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
        Schema::create('profile_business_mgmt', function (Blueprint $table) {
            $table->integer('business_mgmt_id')->autoIncrement();
            $table->integer('business_profile_id')->nullable(false);
            $table->integer('user_id')->nullable(false);
            $table->string('mgmt_name', 255)->nullable();
            $table->string('mgmt_designation', 255)->nullable();
            $table->string('mgmt_email', 255)->nullable();
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
        Schema::dropIfExists('profile_business_mgmt');
    }
};