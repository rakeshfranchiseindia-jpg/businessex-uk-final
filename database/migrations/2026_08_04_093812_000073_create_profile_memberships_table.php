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
        Schema::create('profile_memberships', function (Blueprint $table) {
            $table->integer('membership_id')->autoIncrement();
            $table->integer('user_id')->nullable(false);
            $table->tinyInteger('profile_type')->nullable(false);
            $table->integer('profile_id')->nullable(false);
            $table->integer('order_no')->nullable(false);
            $table->string('amount', 15)->default('');
            $table->smallInteger('membership_type')->nullable(false);
            $table->tinyInteger('payment_source')->nullable(false);
            $table->string('payment_comments', 255)->nullable();
            $table->string('interaction_credits', 55)->nullable();
            $table->string('instant_responses', 55)->nullable();
            $table->timestamp('activation_date')->nullable(false)->useCurrent()->useCurrentOnUpdate();
            $table->timestamp('expiry_date')->nullable();
            $table->tinyInteger('is_active')->nullable(false);
            $table->tinyInteger('upg_source')->nullable(false)->default(1)->comment('1 - Direct online, 2 - Bx offline, 3 - Events');
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
        Schema::dropIfExists('profile_memberships');
    }
};