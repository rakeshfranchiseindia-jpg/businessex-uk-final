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
        Schema::create('content_tags_assigned', function (Blueprint $table) {
            $table->integer('assigned_id')->autoIncrement();
            $table->integer('content_type')->nullable(false)->comment('1:Article, 2:News');
            $table->integer('content_id')->nullable(false);
            $table->integer('tag_id')->nullable(false);
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
        Schema::dropIfExists('content_tags_assigned');
    }
};