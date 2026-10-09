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
        Schema::create('ind_pref_business', function (Blueprint $table) {
            $table->integer('business_ind_pref_id')->autoIncrement();
            $table->integer('business_profile_id')->nullable(false);
            $table->integer('user_id')->nullable(false);
            $table->integer('parent_category_id')->nullable(false);
            $table->integer('sub_category_id')->nullable(false);
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
        Schema::dropIfExists('ind_pref_business');
    }
};