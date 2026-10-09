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
        Schema::create('contact_startup', function (Blueprint $table) {
            $table->integer('contact_id')->autoIncrement();
            $table->string('profile_str', 55)->nullable(false);
            $table->integer('user_id')->nullable(false);
            $table->integer('profile_id')->nullable(false);
            $table->string('contact_name', 100)->nullable(false);
            $table->string('contact_designation', 100)->nullable(false);
            $table->string('contact_mobile', 12)->nullable(false);
            $table->string('contact_email', 100)->nullable(false);
            $table->string('contact_company', 255)->nullable(false);
            $table->integer('contact_investment')->nullable(false);
            $table->string('contact_purchase_time', 100)->nullable(false);
            $table->string('contact_comment', 155)->nullable(false);
            $table->integer('contact_viewed')->nullable(false)->default(0);
            $table->integer('contact_response')->nullable();
            $table->integer('contact_status')->nullable();
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
        Schema::dropIfExists('contact_startup');
    }
};