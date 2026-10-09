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
        Schema::create('bx_news', function (Blueprint $table) {
            $table->integer('news_id')->autoIncrement();
            $table->string('news_title', 255)->nullable(false);
            $table->string('short_desc', 255)->nullable(false);
            $table->longText('news_content')->nullable(false);
            $table->integer('author_id')->nullable(false);
            $table->string('image_path', 255)->nullable(false);
            $table->string('listing_image_path', 255)->nullable(false);
            $table->string('news_tags', 255)->nullable();
            $table->tinyInteger('news_status')->nullable(false)->default(0);
            $table->string('seo_title', 255)->nullable(false);
            $table->string('seo_keywords', 255)->nullable(false);
            $table->string('seo_desc', 255)->nullable(false);
            $table->integer('news_views')->nullable(false)->default(0);
            $table->integer('news_comments')->nullable(false)->default(0);
            $table->integer('created_by')->nullable(false)->default(1);
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
        Schema::dropIfExists('bx_news');
    }
};