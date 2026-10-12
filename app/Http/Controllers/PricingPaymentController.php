<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use Throwable;

class PricingPaymentController extends Controller
{
    // Amount in pence (GBP), matching the £ figures shown on the pricing page.
    private const PLAN_AMOUNTS = [
        'premium' => ['monthly' => 2900, 'annual' => 29900],
        'gold' => ['monthly' => 8900, 'annual' => 99900],
    ];

    // tinyint code stored in profile_business.membership_plan etc.
    private const PLAN_CODES = [
        'premium' => 1,
        'gold' => 2,
    ];

    private const PROFILE_TYPE_LEGACY_CODES = [
        'business' => 1,
        'investor' => 2,
        'mentor' => 4,
        'startup' => 7,
    ];

    private const STATUS_INITIATED = 0;

    private const STATUS_SUCCESS = 1;

    private const STATUS_FAILED = 2;

    public function initiate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'selected_plan' => ['required', 'in:premium,gold'],
            'billing_period' => ['required', 'in:monthly,annual'],
            'profile_type' => ['required', 'in:business,investor,mentor,startup'],
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'mobile' => ['required', 'string', 'max:20'],
            'company' => ['required', 'string', 'max:255'],
            'payment_mode' => ['required', 'in:online,upi'],
        ]);

        if (blank(config('services.razorpay.key')) || blank(config('services.razorpay.secret'))) {
            return response()->json([
                'message' => 'Online payment is not available yet. Please try again later.',
            ], 422);
        }

        $amount = self::PLAN_AMOUNTS[$validated['selected_plan']][$validated['billing_period']];
        $orderNo = $this->uniqueOrderNo();

        try {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            $order = $api->order->create([
                'receipt' => $orderNo,
                'amount' => $amount,
                'currency' => 'GBP',
                'payment_capture' => 1,
            ]);
        } catch (Throwable $exception) {
            Log::error('Razorpay order creation failed.', ['exception' => $exception->getMessage()]);

            return response()->json(['message' => 'Could not start the payment. Please try again.'], 500);
        }

        DB::table('online_payments')->insert([
            'order_no' => $orderNo,
            'user_id' => Auth::id() ?? 0,
            'profile_type' => self::PROFILE_TYPE_LEGACY_CODES[$validated['profile_type']],
            'profile_id' => 0,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['mobile'],
            'city' => '',
            'country' => 'United Kingdom',
            'product_details' => ucfirst($validated['selected_plan']).' '.ucfirst($validated['profile_type']).' Membership ('.$validated['billing_period'].')',
            'membership_plan' => $validated['selected_plan'],
            'amount' => number_format($amount / 100, 2, '.', ''),
            'udf' => $order['id'],
            'payment_status' => self::STATUS_INITIATED,
            'payment_mode' => $validated['payment_mode'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'key' => config('services.razorpay.key'),
            'order_id' => $order['id'],
            'amount' => $amount,
            'currency' => 'GBP',
            'order_no' => $orderNo,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
        ]);
    }

    public function verify(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_no' => ['required', 'string'],
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_signature' => ['required', 'string'],
        ]);

        $payment = DB::table('online_payments')->where('order_no', $validated['order_no'])->first();
        if (! $payment || $payment->udf !== $validated['razorpay_order_id']) {
            return response()->json(['message' => 'Payment record not found.'], 404);
        }

        try {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $validated['razorpay_order_id'],
                'razorpay_payment_id' => $validated['razorpay_payment_id'],
                'razorpay_signature' => $validated['razorpay_signature'],
            ]);
        } catch (SignatureVerificationError $exception) {
            DB::table('online_payments')->where('order_no', $validated['order_no'])->update([
                'payment_status' => self::STATUS_FAILED,
                'status_message' => 'Signature verification failed.',
                'updated_at' => now(),
            ]);

            return response()->json(['message' => 'Payment verification failed.'], 422);
        }

        DB::table('online_payments')->where('order_no', $validated['order_no'])->update([
            'payment_status' => self::STATUS_SUCCESS,
            'status_message' => 'razorpay_payment_id:'.$validated['razorpay_payment_id'],
            'updated_at' => now(),
        ]);

        $profileType = array_search((int) $payment->profile_type, self::PROFILE_TYPE_LEGACY_CODES, true) ?: 'business';

        return response()->json([
            'success' => true,
            'redirect' => route("{$profileType}-registration", ['payment_order_no' => $validated['order_no']]),
        ]);
    }

    /**
     * Credits a successful, unclaimed payment to the profile that was just
     * created during registration — called from AuthController::register().
     */
    public static function claimForProfile(string $orderNo, string $type, int $legacyProfileType, string $table, string $idColumn, int $profileId, int $userId): void
    {
        $payment = DB::table('online_payments')
            ->where('order_no', $orderNo)
            ->where('payment_status', self::STATUS_SUCCESS)
            ->where('profile_id', 0)
            ->where('profile_type', $legacyProfileType)
            ->first();

        if (! $payment) {
            return;
        }

        $planCode = self::PLAN_CODES[$payment->membership_plan] ?? null;
        if ($planCode === null) {
            return;
        }

        DB::table($table)->where($idColumn, $profileId)->update([
            'membership_paid' => 1,
            'membership_plan' => $planCode,
            'updated_at' => now(),
        ]);

        DB::table('online_payments')->where('order_no', $orderNo)->update([
            'profile_id' => $profileId,
            'user_id' => $userId,
            'updated_at' => now(),
        ]);
    }

    private function uniqueOrderNo(): string
    {
        do {
            $orderNo = 'BX'.now()->format('ymd').Str::upper(Str::random(8));
        } while (DB::table('online_payments')->where('order_no', $orderNo)->exists());

        return $orderNo;
    }
}
