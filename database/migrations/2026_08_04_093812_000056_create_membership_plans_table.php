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
        Schema::create('membership_plans', function (Blueprint $table) {
            $table->integer('plan_id')->autoIncrement();
            $table->integer('plan_type')->nullable(false);
            $table->string('plan_name', 250)->nullable(false);
            $table->text('plan_desc')->nullable(false);
            $table->integer('profile_type')->nullable(false);
            $table->string('profile_name', 250)->nullable(false);
            $table->smallInteger('validity_in_days')->nullable(false);
            $table->string('plan_amount', 10)->nullable(false);
            $table->string('interaction_credits', 55)->nullable(false);
            $table->string('instant_responses', 55)->nullable(false);
            $table->tinyInteger('is_active')->nullable(false);
            $table->timestamp('deactivated_at')->nullable(false);
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
        Schema::dropIfExists('membership_plans');
    }
};