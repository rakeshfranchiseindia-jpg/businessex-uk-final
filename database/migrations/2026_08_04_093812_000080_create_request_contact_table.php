<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('request_contact', function (Blueprint $table) {
            $table->integer('request_id')->autoIncrement();
            $table->string('profile_str', 55)->nullable(false);
            $table->integer('receiver')->nullable(false);
            $table->integer('sender')->nullable(false);
            $table->integer('receiver_profile_type')->nullable(false);
            $table->integer('sender_profile_type')->nullable(false);

            // ✅ Corrected enum definition
            $table->enum('status', ['1','2','3'])
                  ->default('1')
                  ->comment('1:New(Pending), 2:Accepted, 3:Rejected');

            $table->tinyInteger('viewed_status')
                  ->default(0)
                  ->comment('0:not viewed, 1:viewed');

            $table->text('msg')->nullable(false);
            $table->string('timestamp', 200)->nullable(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('request_contact');
    }
};