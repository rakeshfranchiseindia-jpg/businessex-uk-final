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
        Schema::create('bex_shorts', function (Blueprint $table) {
            $table->integer('bex_id')->autoIncrement();
            $table->string('bex_title', 100)->nullable(false);
            $table->text('bex_description')->nullable(false);
            $table->integer('author_id')->nullable(false);
            $table->string('image_path', 250)->nullable();
            $table->string('reference_page_name', 150)->nullable();
            $table->string('reference_page_link', 250)->nullable();
            $table->string('associated_tag', 255)->nullable();
            $table->string('seo_title', 255)->nullable();
            $table->string('seo_keywords', 255)->nullable();
            $table->string('seo_desc', 255)->nullable();
            $table->tinyInteger('status')->nullable(false);
            $table->tinyInteger('created_by')->nullable(false);
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
        Schema::dropIfExists('bex_shorts');
    }
};