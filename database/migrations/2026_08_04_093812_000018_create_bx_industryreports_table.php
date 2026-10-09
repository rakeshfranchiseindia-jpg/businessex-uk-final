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
        Schema::create('bx_industryreports', function (Blueprint $table) {
            $table->integer('industryreport_id')->autoIncrement();
            $table->string('industryreport_title', 255)->nullable(false);
            $table->string('industryreport_home_title', 100)->nullable(false);
            $table->integer('industry_sector')->nullable(false);
            $table->text('short_desc')->nullable(false);
            $table->text('industryreport_content')->nullable(false);
            $table->integer('author_id')->nullable(false);
            $table->string('image_path', 255)->nullable(false);
            $table->string('listing_image_path', 255)->nullable(false);
            $table->string('industryreport_pdf_path', 255)->nullable(false);
            $table->string('industryreport_tags', 255)->nullable(false);
            $table->tinyInteger('industryreport_status')->nullable(false)->default(0);
            $table->string('seo_title', 255)->nullable(false);
            $table->string('seo_keywords', 255)->nullable(false);
            $table->string('seo_desc', 255)->nullable(false);
            $table->integer('industryreport_views')->nullable(false)->default(0);
            $table->integer('industryreport_comments')->nullable(false)->default(0);
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
        Schema::dropIfExists('bx_industryreports');
    }
};