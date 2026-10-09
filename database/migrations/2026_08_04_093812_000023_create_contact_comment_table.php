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
        Schema::create('contact_comment', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('contact_id')->nullable(false)->comment('primary key of respective profile_contact table');
            $table->integer('contact_type')->nullable(false)->comment('1:Business, 2:Investor, 3:Lender, 4:Mentor, 5:Incubation, 6:Broker, 7:Startup');
            $table->integer('commented_by')->nullable(false);
            $table->tinyInteger('contact_status')->nullable(false)->comment('1 : Follow Up, 2 : Closed, 3 : Not Interested, 4 : Not Responding, 5 : Wrong No, 6 : Switch Off, 7 : Other');
            $table->dateTime('reminder')->nullable();
            $table->tinyInteger('reminder_sent')->nullable()->comment('1: sent');
            $table->tinyInteger('contact_response')->nullable()->comment('1:Hot, 2:Cold, 3:Warm');
            $table->text('comment')->nullable(false);
            $table->timestamp('comment_date')->nullable(false)->useCurrent();
            $table->timestamp('updated_date')->nullable(false)->useCurrent()->useCurrentOnUpdate();
            $table->tinyInteger('status')->nullable(false)->default(0)->comment('1 for active, 0 for deactive ');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_comment');
    }
};