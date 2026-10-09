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
        Schema::create('admin_profile_rejected', function (Blueprint $table) {
            $table->integer('prof_reject_id')->autoIncrement();
            $table->string('profile_type', 191)->nullable(false);
            $table->string('profile_id', 191)->nullable(false);
            $table->string('admin_email', 191)->nullable(false);
            $table->string('rejected_reason', 191)->nullable(false);
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
        Schema::dropIfExists('admin_profile_rejected');
    }
};