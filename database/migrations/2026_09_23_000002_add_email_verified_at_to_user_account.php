<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_account', function (Blueprint $table) {
            $table->timestamp('email_verified_at')->nullable()->after('verify_token');
        });
    }

    public function down()
    {
        Schema::table('user_account', function (Blueprint $table) {
            $table->dropColumn('email_verified_at');
        });
    }
};
