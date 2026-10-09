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
        Schema::create('profile_incubators', function (Blueprint $table) {
            $table->integer('incubator_id')->autoIncrement();
            $table->string('incubator_profile_str', 20)->nullable(false);
            $table->integer('user_id')->nullable(false);
            $table->string('incubator_name', 100)->nullable(false);
            $table->string('incubator_mobile', 10)->nullable();
            $table->string('incubator_email', 100)->nullable();
            $table->string('incubator_location', 155)->nullable();
            $table->string('incubator_city', 100)->nullable();
            $table->string('incubator_state', 100)->nullable();
            $table->string('incubator_country', 100)->nullable();
            $table->string('incubator_adv_headline', 255)->nullable();
            $table->string('incubator_intro', 255)->nullable();
            $table->string('incubator_company', 255)->nullable();
            $table->string('incubator_designation', 255)->nullable();
            $table->text('incubator_profile_summary')->nullable();
            $table->string('incubator_company_logo', 255)->nullable();
            $table->string('estb_year', 15)->nullable();
            $table->string('company_city', 155)->nullable();
            $table->string('company_state', 155)->nullable();
            $table->string('company_country', 155)->nullable();
            $table->string('company_pincode', 155)->nullable();
            $table->string('signature', 255)->nullable();
            $table->string('company_website', 155)->nullable();
            $table->tinyInteger('membership_paid')->nullable(false)->default(0);
            $table->tinyInteger('membership_plan')->nullable(false)->default(0);
            $table->tinyInteger('incubator_profile_status')->nullable(false);
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
        Schema::dropIfExists('profile_incubators');
    }
};