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
        Schema::create('bx_coupon', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('coupon_code', 255)->nullable(false);
            $table->tinyInteger('discount_type')->nullable(false)->default(1)->comment('1:percentage, 2:flat');
            $table->integer('discount_amount')->nullable(false);
            $table->tinyInteger('user_type')->nullable()->comment('1: New User, 2:Existing User');
            $table->string('profile_type', 255)->nullable()->comment('1:Business, 2:Investor, 3:Lender, 4:Mentor, 5:Incubation, 6:Broker, 7:Startup');
            $table->string('membership', 11)->nullable()->comment('0:Activation, 1:premium, 2:Gold, 3:Platinum');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->integer('max_redemption')->nullable()->comment('Maximum time coupon can be redeemed');
            $table->integer('redemption_number')->default(0)->comment('No of time coupon used');
            $table->tinyInteger('platform')->nullable(false)->default(1)->comment('1:web, 2:app');
            $table->tinyInteger('is_active')->nullable(false);
            $table->timestamps();
            $table->unique('coupon_code');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bx_coupon');
    }
};