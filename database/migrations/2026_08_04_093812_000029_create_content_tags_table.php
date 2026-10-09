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
        Schema::create('content_tags', function (Blueprint $table) {
            $table->integer('tag_id')->autoIncrement();
            $table->string('tag_name', 255)->nullable(false);
            $table->string('tag_slug', 255)->nullable(false);
            $table->integer('tag_status')->nullable(false)->default(0);
            $table->timestamps();
            $table->unique('tag_slug');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('content_tags');
    }
};