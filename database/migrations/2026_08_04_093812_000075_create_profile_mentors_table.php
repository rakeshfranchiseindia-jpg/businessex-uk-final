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
        Schema::create('profile_mentors', function (Blueprint $table) {
            $table->integer('mentor_id')->autoIncrement();
            $table->string('mentor_profile_str', 20)->nullable(false);
            $table->integer('user_id')->nullable(false);
            $table->string('mentor_name', 100)->nullable(false);
            $table->string('mentor_mobile', 20)->nullable();
            $table->string('mentor_email', 100)->nullable();
            $table->string('mentor_location', 155)->nullable();
            $table->string('mentor_city', 100)->nullable();
            $table->string('mentor_state', 100)->nullable();
            $table->string('mentor_country', 100)->nullable();
            $table->string('mentor_adv_headline', 255)->nullable();
            $table->string('mentor_intro', 255)->nullable();
            $table->tinyInteger('mentor_occupation')->nullable();
            $table->string('mentor_company', 255)->nullable();
            $table->string('mentor_designation', 255)->nullable();
            $table->text('mentor_profile_summary')->nullable();
            $table->string('mentor_profile_pic', 255)->nullable();
            $table->string('mentor_linkedin', 255)->nullable();
            $table->tinyInteger('mentor_profile_status')->nullable(false);
            $table->tinyInteger('membership_paid')->nullable(false)->default(0);
            $table->tinyInteger('membership_plan')->nullable(false)->default(0);
            $table->string('mentor_profile_pic_name', 255)->nullable();
            $table->string('trackid', 30)->nullable();
            $table->string('utm_source', 30)->nullable();
            $table->string('utm_medium', 30)->nullable();
            $table->string('utm_campaign', 30)->nullable();
            $table->integer('contact_response')->nullable();
            $table->integer('contact_status')->nullable();
            $table->integer('activated_by')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile_mentors');
    }
};