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
        Schema::create('bx_services', function (Blueprint $table) {
            $table->integer('payment_id')->autoIncrement();
            $table->string('order_no', 30)->nullable(false);
            $table->integer('user_id')->nullable();
            $table->string('name', 150)->nullable(false);
            $table->string('email', 150)->nullable(false);
            $table->string('phone', 20)->nullable(false);
            $table->string('company', 255)->nullable(false);
            $table->string('designation', 255)->nullable();
            $table->string('event_city', 255)->nullable();
            $table->date('event_date')->nullable();
            $table->string('event_timing', 255)->nullable();
            $table->string('event_topic', 255)->nullable();
            $table->tinyInteger('is_member')->nullable()->comment('0:No, 1:Yes');
            $table->string('amount', 30)->nullable(false);
            $table->tinyInteger('service_type')->nullable(false)->default(1)->comment('1:Business Valuation, 2:Business Plan');
            $table->string('product_details', 255)->nullable();
            $table->string('udf', 255)->nullable();
            $table->tinyInteger('payment_status')->nullable(false)->default(0);
            $table->string('payment_mode', 20)->nullable();
            $table->integer('contact_response')->nullable();
            $table->integer('contact_status')->nullable();
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
        Schema::dropIfExists('bx_services');
    }
};