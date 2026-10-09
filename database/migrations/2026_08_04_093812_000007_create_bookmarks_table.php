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
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->integer('bookmark_id')->autoIncrement();
            $table->integer('user_id')->nullable(false);
            $table->integer('profile_id')->nullable(false);
            $table->integer('profile_type')->nullable(false);
            $table->string('profile_str', 75)->nullable(false);
            $table->tinyInteger('bookmark_status')->nullable(false);
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
        Schema::dropIfExists('bookmarks');
    }
};