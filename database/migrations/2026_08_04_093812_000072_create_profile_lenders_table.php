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
        Schema::create('profile_lenders', function (Blueprint $table) {
            $table->integer('lender_id')->autoIncrement();
            $table->string('lender_profile_str', 20)->nullable(false);
            $table->integer('user_id')->nullable(false);
            $table->string('lender_name', 100)->nullable(false);
            $table->string('lender_mobile', 20)->nullable(false);
            $table->string('lender_email', 100)->nullable(false);
            $table->string('lender_location', 100)->nullable();
            $table->string('lender_city', 155)->nullable();
            $table->string('lender_state', 155)->nullable();
            $table->string('lender_country', 155)->nullable();
            $table->string('lender_adv_headline', 255)->nullable(false);
            $table->string('lender_intro', 255)->nullable(false);
            $table->tinyInteger('lender_type')->nullable();
            $table->string('lender_occupation', 255)->nullable();
            $table->decimal('lending_capacity', 16, 4)->default(0.0000);
            $table->tinyInteger('lending_interest_rate')->nullable();
            $table->string('loan_offerings', 255)->nullable();
            $table->string('prof_summary', 255)->nullable();
            $table->string('nbfc_contact_name', 255)->nullable();
            $table->string('nbfc_contact_designation', 255)->nullable();
            $table->string('nbfc_comp_name', 255)->nullable();
            $table->tinyInteger('nbfc_type')->nullable();
            $table->string('nbfc_branch', 100)->nullable();
            $table->string('nbfc_country', 155)->nullable();
            $table->string('nbfc_state', 100)->nullable();
            $table->string('nbfc_city', 100)->nullable();
            $table->string('nbfc_pincode', 15)->nullable();
            $table->string('nbfc_website', 100)->nullable();
            $table->string('nbfc_about', 255)->nullable();
            $table->string('profile_pic_path', 255)->nullable();
            $table->string('nbfc_corporate_profile_path', 255)->nullable();
            $table->tinyInteger('rbi_registered')->nullable();
            $table->string('rbi_registered_no', 25)->nullable();
            $table->tinyInteger('lender_profile_status')->nullable(false);
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
        Schema::dropIfExists('profile_lenders');
    }
};