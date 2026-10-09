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
        Schema::create('businessex_newsletter', function (Blueprint $table) {
            $table->integer('newsletter_id')->unsigned()->autoIncrement();
            $table->integer('user_id')->nullable(false);
            $table->string('email', 255)->nullable(false);
            $table->enum('status', ['S','P','U'])->nullable(false)->default('P');
            $table->string('unsubscribe_reason', 255)->nullable();
            $table->timestamps();
            $table->unique('email');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('businessex_newsletter');
    }
};