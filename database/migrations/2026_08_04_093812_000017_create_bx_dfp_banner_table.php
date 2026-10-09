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
        Schema::create('bx_dfp_banner', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('dfp_id', 255)->nullable(false);
            $table->string('dfp_slot', 255)->nullable(false);
            $table->tinyInteger('page')->nullable(false);
            $table->tinyInteger('location')->nullable(false);
            $table->integer('width')->nullable(false);
            $table->integer('height')->nullable(false);
            $table->tinyInteger('is_active')->nullable(false)->comment('0:Inactive 1:Active');
            $table->timestamps();
            $table->unique(['page', 'location', 'is_active'], 'unique_index');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bx_dfp_banner');
    }
};