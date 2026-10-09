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
        Schema::create('businessex_contactus', function (Blueprint $table) {
            $table->integer('contact_id')->autoIncrement();
            $table->string('contact_name', 100)->nullable(false);
            $table->string('contact_email', 100)->nullable(false);
            $table->string('contact_mobile', 12)->nullable(false);
            $table->string('contact_comment', 155)->nullable(false);
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
        Schema::dropIfExists('businessex_contactus');
    }
};