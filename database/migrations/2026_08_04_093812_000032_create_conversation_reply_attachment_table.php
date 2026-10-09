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
        Schema::create('conversation_reply_attachment', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('reply_id')->nullable(false);
            $table->string('file_name', 255)->nullable(false);
            $table->string('file_size', 100)->nullable(false);
            $table->text('file_path')->nullable(false);
            $table->enum('is_active', ['1','2'])->nullable(false)->default('1');
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
        Schema::dropIfExists('conversation_reply_attachment');
    }
};