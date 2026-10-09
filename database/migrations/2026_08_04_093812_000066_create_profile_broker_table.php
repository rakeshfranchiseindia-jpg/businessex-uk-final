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
        Schema::create('profile_broker', function (Blueprint $table) {
            $table->integer('broker_id')->autoIncrement();
            $table->string('broker_profile_str', 20)->nullable(false);
            $table->integer('user_id')->nullable(false);
            $table->string('broker_name', 100)->nullable(false);
            $table->string('broker_mobile', 12)->nullable();
            $table->string('broker_email', 100)->nullable();
            $table->integer('broker_profile_type')->nullable(false);
            $table->string('broker_company', 255)->nullable();
            $table->integer('estb_year')->nullable();
            $table->string('emp_count', 35)->nullable();
            $table->string('company_city', 100)->nullable(false);
            $table->string('company_state', 100)->nullable(false);
            $table->string('company_country', 100)->nullable(false);
            $table->string('company_website', 150)->nullable();
            $table->string('ofc_city', 100)->nullable();
            $table->string('ofc_state', 100)->nullable();
            $table->string('ofc_country', 100)->nullable(false);
            $table->string('ofc_pincode', 10)->nullable();
            $table->string('prof_summary', 255)->nullable();
            $table->integer('prof_exp_year')->nullable(false);
            $table->string('broker_company_logo', 255)->nullable();
            $table->tinyInteger('broker_profile_status')->nullable();
            $table->tinyInteger('membership_paid')->nullable(false)->default(0);
            $table->tinyInteger('membership_plan')->nullable(false)->default(0);
            $table->integer('contact_response')->nullable();
            $table->string('utm_source', 30)->nullable();
            $table->integer('contact_status')->nullable();
            $table->integer('activated_by')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->dateTime('last_login_at')->nullable();
            $table->timestamps();
            $table->unique('broker_profile_str');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile_broker');
    }
};