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
        Schema::create('article_news_images', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement();
            $table->integer('content_id')->nullable(false)->comment('article/news id');
            $table->tinyInteger('type')->nullable(false)->default(1)->comment('1: Article, 2: News');
            $table->string('img_path', 255)->nullable(false);
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
        Schema::dropIfExists('article_news_images');
    }
};