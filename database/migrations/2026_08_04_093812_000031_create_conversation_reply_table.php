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
        Schema::create('conversation_reply', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->text('reply')->nullable(false);
            $table->integer('from_id')->nullable(false);
            $table->integer('to_id')->nullable(false);
            $table->timestamp('timestamp')->useCurrent();
            $table->integer('request_id')->nullable(false);
            $table->enum('readstatus', ['1','2'])->nullable(false)->default('1');
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
        Schema::dropIfExists('conversation_reply');
    }
};