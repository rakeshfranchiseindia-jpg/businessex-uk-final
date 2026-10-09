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
        Schema::create('online_payments', function (Blueprint $table) {
            $table->integer('payment_id')->autoIncrement();
            $table->string('order_no', 30)->nullable(false);
            $table->integer('user_id')->nullable(false);
            $table->integer('profile_type')->nullable(false);
            $table->integer('profile_id')->nullable(false);
            $table->integer('coupon_id')->nullable();
            $table->string('name', 150)->nullable(false);
            $table->string('email', 150)->nullable(false);
            $table->string('phone', 20)->nullable(false);
            $table->string('city', 100)->nullable(false);
            $table->string('country', 100)->nullable();
            $table->string('product_details', 255)->nullable(false);
            $table->string('membership_plan', 255)->nullable(false);
            $table->string('amount', 30)->nullable(false);
            $table->string('udf', 255)->nullable();
            $table->tinyInteger('payment_status')->nullable(false)->default(0);
            $table->string('payment_mode', 20)->nullable();
            $table->tinyInteger('addon_one')->nullable();
            $table->tinyInteger('addon_two')->nullable();
            $table->tinyInteger('addon_three')->nullable();
            $table->tinyInteger('addon_four')->nullable();
            $table->tinyInteger('addon_five')->nullable();
            $table->tinyInteger('addon_six')->nullable();
            $table->string('status_message', 255)->nullable();
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
        Schema::dropIfExists('online_payments');
    }
};