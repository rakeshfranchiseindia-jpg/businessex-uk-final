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
        Schema::create('news', function (Blueprint $table) {
            $table->integer('newsID')->autoIncrement();
            $table->string('homeTitle', 255)->nullable(false);
            $table->string('title', 255)->nullable();
            $table->string('shortDesc', 255)->nullable(false);
            $table->text('content')->nullable(false);
            $table->string('image', 200)->nullable(false);
            $table->string('tags', 255)->nullable(false);
            $table->string('newskeywords', 255)->nullable(false);
            $table->integer('totalComment')->nullable(false);
            $table->integer('totalVotes')->nullable(false);
            $table->integer('views')->nullable(false)->default(0);
            $table->enum('status', ['A','D'])->nullable(false)->default('D');
            $table->string('seoTitle', 255)->nullable(false);
            $table->string('seoKeywords', 255)->nullable(false);
            $table->string('seoDescription', 255)->nullable(false);
            $table->dateTime('news_date')->nullable(false);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
    }
};