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
        Schema::create('news_list', function (Blueprint $table) {
            $table->integer('news_id')->autoIncrement();
            $table->integer('prev_id')->nullable(false);
            $table->string('news_type', 25)->nullable(false);
            $table->string('kicker', 155)->nullable(false);
            $table->string('title', 128)->nullable(false);
            $table->string('homeTitle', 255)->nullable(false);
            $table->string('shortDesc', 255)->nullable(false);
            $table->text('content')->nullable(false);
            $table->string('image', 155)->nullable(false);
            $table->string('slug', 128)->nullable();
            $table->string('related_brand', 255)->nullable(false);
            $table->timestamp('time')->useCurrent();
            $table->integer('views')->default(0);
            $table->smallInteger('totalComment')->nullable(false);
            $table->smallInteger('totalVotes')->nullable(false);
            $table->integer('facebook_shared')->nullable(false);
            $table->tinyInteger('status')->default(0);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_list');
    }
};