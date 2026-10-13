<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $duplicateOrders = DB::table('online_payments')
            ->select('order_no')
            ->groupBy('order_no')
            ->havingRaw('COUNT(*) > 1')
            ->count();

        if ($duplicateOrders > 0) {
            throw new RuntimeException("online_payments has {$duplicateOrders} duplicated order_no value(s); resolve them before adding the unique key.");
        }

        Schema::table('online_payments', function (Blueprint $table): void {
            // One Razorpay payment can settle at most one order; together with
            // the status check in MembershipPaymentService this makes payment
            // completion idempotent.
            $table->string('razorpay_payment_id', 64)->nullable()->after('udf');
            $table->unique('razorpay_payment_id');
            $table->unique('order_no');
            $table->index('udf');
        });

        Schema::table('profile_memberships', function (Blueprint $table): void {
            // Deactivating an old membership must not rewrite when it started.
            $table->timestamp('activation_date')->nullable(false)->useCurrent()->change();
            $table->index(['user_id', 'profile_type', 'profile_id'], 'profile_memberships_owner_index');
        });
    }

    public function down(): void
    {
        Schema::table('profile_memberships', function (Blueprint $table): void {
            $table->dropIndex('profile_memberships_owner_index');
            $table->timestamp('activation_date')->nullable(false)->useCurrent()->useCurrentOnUpdate()->change();
        });

        Schema::table('online_payments', function (Blueprint $table): void {
            $table->dropIndex(['udf']);
            $table->dropUnique(['order_no']);
            $table->dropUnique(['razorpay_payment_id']);
            $table->dropColumn('razorpay_payment_id');
        });
    }
};
