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
        Schema::create('mobile_verification', function (Blueprint $table) {
            $table->integer('mob_verify_id')->autoIncrement();
            $table->integer('user_id')->nullable(false);
            $table->string('mobile_no', 20)->nullable(false);
            $table->string('otp_code', 10)->nullable(false);
            $table->string('smspg_response', 255)->nullable(false);
            $table->tinyInteger('is_verified')->nullable(false)->default(0);
            $table->timestamp('verified_at')->nullable(false);
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
        Schema::dropIfExists('mobile_verification');
    }
};