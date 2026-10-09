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
        Schema::create('ind_pref_investors_fihl', function (Blueprint $table) {
            $table->integer('inv_ind_pref_id')->nullable(false)->default(0);
            $table->integer('investor_profile_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('parent_category_id')->nullable(false);
            $table->integer('sub_category_id')->nullable(false);
            $table->integer('parent_category_id2')->nullable(false);
            $table->integer('sub_category_id2')->nullable(false);
            $table->integer('parent_category_id3')->nullable(false);
            $table->integer('sub_category_id3')->nullable(false);
            $table->string('email', 200)->nullable(false);
            $table->decimal('invest_min', 16, 4)->nullable(false)->default(0.0000);
            $table->decimal('invest_max', 16, 4)->nullable(false)->default(0.0000);
            $table->index('user_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ind_pref_investors_fihl');
    }
};