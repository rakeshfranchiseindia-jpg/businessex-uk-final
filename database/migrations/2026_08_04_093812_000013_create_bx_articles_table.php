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
        Schema::create('bx_articles', function (Blueprint $table) {
            $table->integer('article_id')->autoIncrement();
            $table->string('article_title', 255)->nullable(false);
            $table->text('short_desc')->nullable(false);
            $table->longText('article_content')->nullable(false);
            $table->integer('author_id')->nullable(false);
            $table->string('image_path', 255)->nullable(false);
            $table->string('listing_image_path', 255)->nullable(false);
            $table->string('article_tags', 255)->nullable();
            $table->tinyInteger('article_status')->nullable(false)->default(0);
            $table->string('seo_title', 255)->nullable(false);
            $table->string('seo_keywords', 255)->nullable(false);
            $table->string('seo_desc', 255)->nullable(false);
            $table->integer('article_views')->nullable(false)->default(0);
            $table->integer('article_comments')->nullable(false)->default(0);
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
        Schema::dropIfExists('bx_articles');
    }
};