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
        Schema::create('industry_categories', function (Blueprint $table) {
            $table->integer('cat_id')->autoIncrement();
            $table->string('category_name', 255)->nullable(false);
            $table->string('category_slug', 255)->nullable(false);
            $table->integer('parent_id')->default(0);
            $table->tinyInteger('category_status')->nullable(false)->default(1);
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
        Schema::dropIfExists('industry_categories');
    }
};