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
        Schema::create('news_comments', function (Blueprint $table) {
            $table->integer('comment_id')->autoIncrement();
            $table->integer('news_id')->nullable(false);
            $table->string('comment_name', 55)->nullable(false);
            $table->string('comment_email', 55)->nullable(false);
            $table->text('comment_detail')->nullable(false);
            $table->tinyInteger('comment_status')->nullable(false)->default(0);
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
        Schema::dropIfExists('news_comments');
    }
};