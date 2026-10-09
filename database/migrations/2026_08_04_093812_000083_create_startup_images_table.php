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
        Schema::create('startup_images', function (Blueprint $table) {
            $table->integer('startup_image_id')->autoIncrement();
            $table->integer('startup_id')->nullable(false);
            $table->tinyInteger('type')->nullable(false)->default(1)->comment('1: image, 2: document');
            $table->string('startup_img_path', 255)->nullable(false);
            $table->string('startup_img_name', 255)->nullable();
            $table->tinyInteger('is_active')->nullable(false)->default(0)->comment('0: inactive, 1:active');
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
        Schema::dropIfExists('startup_images');
    }
};