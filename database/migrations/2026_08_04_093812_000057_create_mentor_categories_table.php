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
        Schema::create('mentor_categories', function (Blueprint $table) {
            $table->integer('mentor_category_id')->autoIncrement();
            $table->string('mentor_category_name', 150)->nullable(false);
            $table->string('category_slug', 255)->nullable(false);
            $table->integer('mentor_parent_id')->nullable();
            $table->tinyInteger('mentor_category_status')->nullable(false);
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
        Schema::dropIfExists('mentor_categories');
    }
};