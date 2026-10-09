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
        Schema::create('bx_author', function (Blueprint $table) {
            $table->integer('author_id')->autoIncrement();
            $table->string('author_name', 255)->nullable(false);
            $table->string('author_email', 255)->nullable(false);
            $table->string('author_desig', 255)->nullable(false);
            $table->string('author_dept', 255)->nullable(false);
            $table->tinyInteger('is_active')->nullable(false);
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
        Schema::dropIfExists('bx_author');
    }
};