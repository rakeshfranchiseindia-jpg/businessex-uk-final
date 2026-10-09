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
        Schema::create('mentor_expertise', function (Blueprint $table) {
            $table->integer('mentor_expert_id')->autoIncrement();
            $table->integer('mentor_id')->nullable(false);
            $table->integer('user_id')->nullable(false);
            $table->tinyInteger('exp_years')->nullable(false);
            $table->integer('exp_industry')->nullable(false);
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mentor_expertise');
    }
};