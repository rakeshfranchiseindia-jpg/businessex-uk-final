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
        Schema::create('profile_business', function (Blueprint $table) {
            $table->integer('business_id')->autoIncrement();
            $table->string('business_profile_str', 20)->nullable(false);
            $table->integer('user_id')->nullable(false);
            $table->string('seller_name', 100)->nullable(false);
            $table->string('seller_designation', 100)->nullable();
            $table->string('seller_mobile', 12)->nullable();
            $table->string('seller_email', 100)->nullable();
            $table->string('advmt_headline', 255)->nullable();
            $table->string('seller_intro', 255)->nullable();
            $table->string('seller_company', 255)->nullable();
            $table->integer('estb_year')->nullable();
            $table->string('emp_count', 50)->nullable();
            $table->string('entity_type', 80)->nullable();
            $table->string('business_type', 80)->nullable();
            $table->string('industry_sector', 255)->nullable();
            $table->string('business_website', 150)->nullable();
            $table->text('facilities_desc')->nullable();
            $table->decimal('annual_sales', 16, 4)->default(0.0000);
            $table->decimal('ebitda', 16, 4)->default(0.0000);
            $table->string('gross_profit', 20)->nullable();
            $table->string('inventory_value', 20)->nullable();
            $table->decimal('ebitda_margin', 16, 4)->default(0.0000);
            $table->string('rentals', 20)->nullable();
            $table->text('company_summary')->nullable();
            $table->string('director_name', 75)->nullable();
            $table->string('director_email', 100)->nullable();
            $table->string('director_designation', 75)->nullable();
            $table->string('ofc_address', 255)->nullable();
            $table->string('ofc_city', 100)->nullable();
            $table->string('ofc_state', 100)->nullable();
            $table->string('ofc_country', 100)->nullable(false);
            $table->string('ofc_pincode', 10)->nullable();
            $table->string('business_pitch', 255)->nullable();
            $table->integer('seeking_investors')->nullable();
            $table->tinyInteger('seeking_buyers')->nullable();
            $table->tinyInteger('seeking_loan')->nullable();
            $table->tinyInteger('seeking_mentors')->nullable();
            $table->tinyInteger('seeking_accelerators')->nullable();
            $table->decimal('buyer_sell_price', 16, 4)->nullable();
            $table->string('buyer_sell_reason', 255)->nullable();
            $table->integer('inv_for')->nullable();
            $table->decimal('inv_asking_price', 16, 4)->default(0.0000);
            $table->string('inv_stake', 10)->nullable();
            $table->string('inv_reason', 255)->nullable();
            $table->string('loan_collateral_details', 255)->nullable();
            $table->decimal('loan_amount', 16, 4)->nullable();
            $table->string('loan_repayment_period', 20)->nullable();
            $table->string('loan_interest_rate', 5)->nullable();
            $table->string('loan_reason', 255)->nullable();
            $table->string('loan_existing', 255)->nullable();
            $table->string('mentor_req_details', 255)->nullable();
            $table->string('accel_req_details', 255)->nullable();
            $table->decimal('accel_inv_req', 16, 4)->nullable();
            $table->string('accel_time_period', 20)->nullable();
            $table->string('seller_prof_pic', 255)->nullable();
            $table->string('seller_prof_thumb_pic', 255)->nullable();
            $table->string('seller_prof_thumb_pic1', 255)->nullable();
            $table->string('seller_prof_pic1', 255)->nullable();
            $table->string('seller_doc_path', 255)->nullable();
            $table->string('seller_doc_path1', 255)->nullable();
            $table->string('seller_doc_path2', 255)->nullable();
            $table->string('seller_doc_path3', 255)->nullable();
            $table->string('seller_doc_path4', 255)->nullable();
            $table->tinyInteger('business_profile_status')->nullable();
            $table->tinyInteger('membership_paid')->default(0);
            $table->tinyInteger('membership_plan')->default(0);
            $table->string('seller_prof_thumb_pic_name', 255)->nullable();
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
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile_business');
    }
};