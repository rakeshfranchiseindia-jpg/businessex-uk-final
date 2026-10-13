<?php

namespace App\Http\Controllers;

use App\Services\Payments\MembershipPaymentService;
use App\Services\Payments\RazorpayGateway;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Throwable;

class PricingPaymentController extends Controller
{
    // Session key holding guest checkout order numbers verified in this
    // browser session; registration may only claim one of these (or one paid
    // with the same email address).
    public const SESSION_VERIFIED_ORDERS = 'payments.verified_orders';

    public function __construct(
        private readonly RazorpayGateway $gateway,
        private readonly MembershipPaymentService $payments,
    ) {}

    public function initiate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'selected_plan' => ['required', 'in:premium,gold'],
            'billing_period' => ['required', 'in:monthly,annual'],
            'profile_type' => ['required', 'in:business,investor,mentor,startup'],
            'name' => ['required', 'string', 'max:150', "regex:/^[A-Za-z][A-Za-z .'-]*$/"],
            'email' => ['required', 'email', 'max:150'],
            'mobile' => ['required', 'string', 'max:20'],
            'company' => ['required', 'string', 'max:255', "regex:/^[A-Za-z][A-Za-z .'-]*$/"],
            'payment_mode' => ['required', 'in:online,upi'],
        ]);

        if (! $this->gateway->isConfigured()) {
            return response()->json([
                'message' => 'Online payment is not available yet. Please try again later.',
            ], 422);
        }

        $amount = MembershipPaymentService::PLAN_AMOUNTS[$validated['selected_plan']][$validated['billing_period']];
        $orderNo = $this->uniqueOrderNo();

        try {
            $order = $this->gateway->createOrder($orderNo, $amount, MembershipPaymentService::CURRENCY);
        } catch (Throwable $exception) {
            Log::error('Razorpay order creation failed.', ['exception' => $exception->getMessage()]);

            return response()->json(['message' => 'Could not start the payment. Please try again.'], 500);
        }

        DB::table('online_payments')->insert([
            'order_no' => $orderNo,
            'user_id' => Auth::id() ?? 0,
            'profile_type' => MembershipPaymentService::PROFILE_TYPE_LEGACY_CODES[$validated['profile_type']],
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
            'payment_status' => MembershipPaymentService::STATUS_INITIATED,
            'payment_mode' => $validated['payment_mode'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'key' => $this->gateway->key(),
            'order_id' => $order['id'],
            'amount' => $amount,
            'currency' => MembershipPaymentService::CURRENCY,
            'order_no' => $orderNo,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
        ]);
    }

    public function verify(Request $request): JsonResponse
    {
        $validated = $this->validateVerification($request);

        $payment = DB::table('online_payments')->where('order_no', $validated['order_no'])->first();
        if (! $payment || $payment->udf !== $validated['razorpay_order_id']) {
            return response()->json(['message' => 'Payment record not found.'], 404);
        }

        if (! $this->signatureIsValid($validated)) {
            return response()->json(['message' => 'Payment verification failed.'], 422);
        }

        $this->payments->complete($validated['order_no'], $validated['razorpay_payment_id']);

        $verifiedOrders = (array) $request->session()->get(self::SESSION_VERIFIED_ORDERS, []);
        $request->session()->put(self::SESSION_VERIFIED_ORDERS, array_values(array_unique([...$verifiedOrders, $validated['order_no']])));

        $profileType = $this->payments->profileTypeFor($payment) ?? 'business';

        return response()->json([
            'success' => true,
            'redirect' => route("{$profileType}-registration", ['payment_order_no' => $validated['order_no']]),
        ]);
    }

    /**
     * My Plan page: upgrading an existing profile the user already owns, so
     * (unlike the guest pricing checkout) the payment is linked to that
     * profile from the start — no post-registration "claim" step needed.
     */
    public function initiateUpgrade(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'selected_plan' => ['required', 'in:premium,gold'],
            'billing_period' => ['required', 'in:monthly,annual'],
            'profile_type' => ['required', Rule::in(array_keys(MembershipPaymentService::PROFILE_TABLES))],
            'profile_id' => ['required', 'integer', 'min:1'],
            'payment_mode' => ['required', 'in:online,upi'],
        ]);

        if (! $this->gateway->isConfigured()) {
            return response()->json([
                'message' => 'Online payment is not available yet. Please try again later.',
            ], 422);
        }

        $profileTable = MembershipPaymentService::PROFILE_TABLES[$validated['profile_type']];
        $owned = DB::table($profileTable['table'])
            ->where($profileTable['id'], $validated['profile_id'])
            ->where('user_id', Auth::id())
            ->exists();

        abort_unless($owned, 403, 'You do not have a matching profile to upgrade.');

        $user = Auth::user();
        $amount = MembershipPaymentService::PLAN_AMOUNTS[$validated['selected_plan']][$validated['billing_period']];
        $orderNo = $this->uniqueOrderNo();

        try {
            $order = $this->gateway->createOrder($orderNo, $amount, MembershipPaymentService::CURRENCY);
        } catch (Throwable $exception) {
            Log::error('Razorpay order creation failed (my-plan upgrade).', ['exception' => $exception->getMessage()]);

            return response()->json(['message' => 'Could not start the payment. Please try again.'], 500);
        }

        DB::table('online_payments')->insert([
            'order_no' => $orderNo,
            'user_id' => Auth::id(),
            'profile_type' => MembershipPaymentService::PROFILE_TYPE_LEGACY_CODES[$validated['profile_type']],
            'profile_id' => $validated['profile_id'],
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->mobile ?? '',
            'city' => '',
            'country' => 'United Kingdom',
            'product_details' => ucfirst($validated['selected_plan']).' '.ucfirst($validated['profile_type']).' Membership Upgrade ('.$validated['billing_period'].')',
            'membership_plan' => $validated['selected_plan'],
            'amount' => number_format($amount / 100, 2, '.', ''),
            'udf' => $order['id'],
            'payment_status' => MembershipPaymentService::STATUS_INITIATED,
            'payment_mode' => $validated['payment_mode'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'key' => $this->gateway->key(),
            'order_id' => $order['id'],
            'amount' => $amount,
            'currency' => MembershipPaymentService::CURRENCY,
            'order_no' => $orderNo,
            'name' => $user->name,
            'email' => $user->email,
            'mobile' => $user->mobile ?? '',
        ]);
    }

    public function verifyUpgrade(Request $request): JsonResponse
    {
        $validated = $this->validateVerification($request);

        $payment = DB::table('online_payments')
            ->where('order_no', $validated['order_no'])
            ->where('user_id', Auth::id())
            ->first();

        if (! $payment || $payment->udf !== $validated['razorpay_order_id']) {
            return response()->json(['message' => 'Payment record not found.'], 404);
        }

        if (! $this->signatureIsValid($validated)) {
            return response()->json(['message' => 'Payment verification failed.'], 422);
        }

        $this->payments->complete($validated['order_no'], $validated['razorpay_payment_id']);

        return response()->json([
            'success' => true,
            'redirect' => route('dashboard.my-plan'),
        ]);
    }

    /**
     * @return array{order_no: string, razorpay_order_id: string, razorpay_payment_id: string, razorpay_signature: string}
     */
    private function validateVerification(Request $request): array
    {
        return $request->validate([
            'order_no' => ['required', 'string', 'max:30'],
            'razorpay_order_id' => ['required', 'string', 'max:64'],
            'razorpay_payment_id' => ['required', 'string', 'max:64'],
            'razorpay_signature' => ['required', 'string', 'max:128'],
        ]);
    }

    /**
     * @param  array{order_no: string, razorpay_order_id: string, razorpay_payment_id: string, razorpay_signature: string}  $validated
     */
    private function signatureIsValid(array $validated): bool
    {
        $valid = $this->gateway->isValidPaymentSignature(
            $validated['razorpay_order_id'],
            $validated['razorpay_payment_id'],
            $validated['razorpay_signature'],
        );

        if (! $valid) {
            $this->payments->markSignatureFailed($validated['order_no']);
        }

        return $valid;
    }

    private function uniqueOrderNo(): string
    {
        do {
            $orderNo = 'BX'.now()->format('ymd').Str::upper(Str::random(8));
        } while (DB::table('online_payments')->where('order_no', $orderNo)->exists());

        return $orderNo;
    }
}
