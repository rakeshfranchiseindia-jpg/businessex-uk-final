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
        Schema::create('user_account', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->autoIncrement();
            $table->string('user_rand_id', 20)->nullable();
            $table->string('name', 255)->nullable(false);
            $table->string('email', 255)->nullable();
            $table->string('password', 60)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('location', 60)->nullable();
            $table->string('timezone', 100)->nullable();
            $table->string('company_name', 255)->nullable(false);
            $table->string('designation', 60)->nullable();
            $table->tinyInteger('is_active')->nullable();
            $table->tinyInteger('reg_source')->nullable();
            $table->string('reg_profile', 100)->nullable();
            $table->string('linkedin_id', 30)->nullable();
            $table->string('google_id', 100)->nullable();
            $table->string('facebook_id', 30)->nullable();
            $table->string('profile_pic', 255)->nullable();
            $table->string('verify_token', 200)->nullable();
            $table->string('reset_token', 255)->nullable();
            $table->integer('contact_response')->nullable();
            $table->integer('contact_status')->nullable();
            $table->timestamp('last_notify_at')->useCurrent();
            $table->timestamp('last_login_at')->useCurrent();
            $table->timestamp('reset_token_created_at')->nullable();
            $table->timestamps();
            $table->rememberToken();
            $table->unique('email');
            $table->unique('user_rand_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_account');
    }
};