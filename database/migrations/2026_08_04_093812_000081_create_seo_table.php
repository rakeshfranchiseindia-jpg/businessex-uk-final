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
        Schema::create('seo', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('profile_type')->nullable(false);
            $table->integer('cat_id')->nullable(false);
            $table->text('title')->nullable();
            $table->longText('keyword')->nullable();
            $table->longText('description')->nullable();
            $table->text('meta_description')->nullable();
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
        Schema::dropIfExists('seo');
    }
};