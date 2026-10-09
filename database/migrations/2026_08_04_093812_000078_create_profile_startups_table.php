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
        Schema::create('profile_startups', function (Blueprint $table) {
            $table->integer('startup_id')->autoIncrement();
            $table->string('startup_profile_str', 20)->nullable(false);
            $table->integer('user_id')->nullable(false);
            $table->string('startup_name', 100)->nullable(false);
            $table->string('startup_designation', 100)->nullable();
            $table->string('startup_mobile', 12)->nullable();
            $table->string('startup_email', 100)->nullable();
            $table->string('advmt_headline', 255)->nullable();
            $table->string('startup_intro', 255)->nullable();
            $table->string('name_of_entity', 255)->nullable();
            $table->integer('business_type')->nullable();
            $table->integer('nature_of_entity')->nullable();
            $table->integer('estb_date')->nullable();
            $table->integer('emp_count')->nullable();
            $table->string('signature', 155)->nullable();
            $table->integer('industry_sector')->nullable();
            $table->string('business_website', 150)->nullable();
            $table->text('facilities_desc')->nullable();
            $table->string('annual_sales', 20)->default('');
            $table->string('ebitda', 20)->nullable();
            $table->string('gross_profit', 20)->nullable();
            $table->string('inventory_value', 20)->nullable();
            $table->string('ebitda_margin', 20)->nullable();
            $table->string('rentals', 20)->nullable();
            $table->string('facebook_profile', 155)->nullable();
            $table->string('twitter_profile', 155)->nullable();
            $table->string('linkedin_profile', 155)->nullable();
            $table->string('ofc_address', 255)->nullable();
            $table->string('ofc_city', 100)->nullable();
            $table->string('ofc_state', 100)->nullable();
            $table->string('ofc_country', 155)->nullable();
            $table->string('ofc_pincode', 10)->nullable();
            $table->string('director_name', 75)->nullable();
            $table->string('director_email', 100)->nullable();
            $table->string('director_designation', 75)->nullable();
            $table->string('director_identification', 35)->nullable();
            $table->string('company_stage', 35)->nullable();
            $table->text('customer_problem')->nullable();
            $table->text('product_service')->nullable();
            $table->string('customer_segment', 255)->nullable();
            $table->string('target_market', 255)->nullable();
            $table->string('competitors', 255)->nullable();
            $table->string('competitive_advantage', 255)->nullable();
            $table->string('sales_marketing', 255)->nullable();
            $table->text('company_summary')->nullable();
            $table->integer('seeking_investors')->nullable();
            $table->tinyInteger('seeking_mentorship')->nullable();
            $table->tinyInteger('seeking_loan')->nullable();
            $table->tinyInteger('seeking_acquirers')->nullable();
            $table->tinyInteger('seeking_incubators')->nullable();
            $table->string('business_pitch', 255)->nullable();
            $table->string('buyer_sell_price', 20)->nullable();
            $table->string('buyer_sell_reason', 255)->nullable();
            $table->integer('inv_for')->nullable();
            $table->string('inv_asking_price', 20)->nullable();
            $table->string('inv_stake', 10)->nullable();
            $table->string('inv_reason', 255)->nullable();
            $table->string('loan_collateral_details', 255)->nullable();
            $table->string('loan_amount', 20)->nullable();
            $table->string('loan_repayment_period', 20)->nullable();
            $table->string('loan_interest_rate', 5)->nullable();
            $table->string('loan_reason', 255)->nullable();
            $table->string('mentor_req_details', 255)->nullable();
            $table->string('accel_req_details', 255)->nullable();
            $table->string('accel_inv_req', 20)->nullable();
            $table->string('accel_time_period', 20)->nullable();
            $table->string('startup_prof_pic', 255)->nullable();
            $table->string('startup_prof_pic1', 255)->nullable();
            $table->string('startup_prof_thumb_pic', 255)->nullable();
            $table->string('startup_prof_thumb_pic1', 255)->nullable();
            $table->string('startup_doc_path', 255)->nullable();
            $table->tinyInteger('startup_profile_status')->nullable();
            $table->tinyInteger('membership_paid')->default(0);
            $table->tinyInteger('membership_plan')->default(0);
            $table->string('startup_prof_thumb_pic_name', 255)->nullable();
            $table->string('trackid', 30)->nullable();
            $table->string('utm_source', 30)->nullable();
            $table->string('utm_medium', 30)->nullable();
            $table->string('utm_campaign', 30)->nullable();
            $table->integer('mailer_campaign')->default(0);
            $table->integer('contact_response')->nullable();
            $table->integer('contact_status')->nullable();
            $table->integer('activated_by')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();
            $table->unique('startup_profile_str');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile_startups');
    }
};