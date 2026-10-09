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
        Schema::create('profile_investor', function (Blueprint $table) {
            $table->integer('investor_id')->autoIncrement();
            $table->string('inv_profile_str', 20)->nullable(false);
            $table->integer('user_id')->nullable(false);
            $table->string('inv_name', 100)->nullable();
            $table->string('inv_email', 100)->nullable();
            $table->string('inv_mobile', 20)->nullable();
            $table->string('inv_placeid', 100)->nullable();
            $table->string('inv_city', 100)->nullable();
            $table->string('inv_state', 100)->nullable();
            $table->string('inv_country', 100)->nullable();
            $table->string('inv_headline', 255)->nullable();
            $table->string('inv_intro', 255)->nullable();
            $table->tinyInteger('inv_type')->default(0);
            $table->tinyInteger('firm_type')->nullable();
            $table->decimal('invest_size_min', 16, 4)->default(0.0000);
            $table->decimal('invest_size_max', 16, 4)->default(0.0000);
            $table->tinyInteger('invest_pref')->default(0);
            $table->integer('invest_stake')->nullable();
            $table->tinyInteger('full_acquisition')->default(0);
            $table->decimal('purchase_capacity_min', 16, 4)->default(0.0000);
            $table->decimal('purchase_capacity_max', 16, 4)->default(0.0000);
            $table->string('inv_abt_urself', 255)->nullable();
            $table->string('linkedin_profile', 255)->nullable();
            $table->string('company_name', 255)->nullable();
            $table->string('company_designation', 100)->nullable();
            $table->string('company_placeid', 100)->nullable();
            $table->string('company_city', 100)->nullable();
            $table->string('company_state', 100)->nullable();
            $table->string('company_country', 100)->nullable();
            $table->string('company_pincode', 20)->nullable();
            $table->string('company_website', 255)->nullable();
            $table->string('company_logo_path', 255)->nullable();
            $table->text('company_summary')->nullable();
            $table->string('inv_profile_pic_path', 255)->nullable();
            $table->tinyInteger('inv_profile_status')->nullable(false);
            $table->tinyInteger('membership_paid')->default(0);
            $table->tinyInteger('membership_plan')->default(0);
            $table->string('inv_profile_pic_name', 255)->nullable();
            $table->string('trackid', 30)->nullable();
            $table->string('utm_source', 30)->nullable();
            $table->string('utm_medium', 30)->nullable();
            $table->string('utm_campaign', 30)->nullable();
            $table->integer('contact_response')->nullable();
            $table->integer('contact_status')->nullable();
            $table->integer('activated_by')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->tinyInteger('reg_source')->nullable(false)->default(1)->comment('1:bx, 6:FI');
            $table->timestamps();
            $table->index('user_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile_investor');
    }
};