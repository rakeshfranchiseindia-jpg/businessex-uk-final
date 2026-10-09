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
        Schema::create('admin_user', function (Blueprint $table) {
            $table->integer('admin_id')->unsigned()->autoIncrement();
            $table->string('admin_name', 255)->nullable(false);
            $table->string('admin_email', 191)->nullable(false);
            $table->string('admin_password', 191)->nullable(false);
            $table->string('admin_dept', 191)->nullable(false)->comment('1:Admin, 2:Customer Relationship, 3:Editorial, 4:SEO');
            $table->tinyInteger('admin_role')->nullable(false)->default(1);
            $table->tinyInteger('admin_is_active')->nullable(false);
            $table->timestamps();
            $table->rememberToken();
            $table->unique('admin_email');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_user');
    }
};