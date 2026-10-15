<?php

namespace App\Services\Payments;

use App\Support\PublicContentCache;
use Illuminate\Support\Facades\DB;

/**
 * Applies the outcome of a Razorpay payment to online_payments, the profile
 * row and profile_memberships. Every entry point (browser verify, My Plan
 * verify, webhook, post-registration claim) goes through here so a payment is
 * only ever applied once, however many times or in whatever order those
 * callbacks arrive.
 */
class MembershipPaymentService
{
    public const STATUS_INITIATED = 0;

    public const STATUS_SUCCESS = 1;

    public const STATUS_FAILED = 2;

    public const CURRENCY = 'GBP';

    // Amount in pence (GBP), matching the £ figures shown on the pricing page.
    public const PLAN_AMOUNTS = [
        'premium' => ['monthly' => 2900, 'annual' => 29900],
        'gold' => ['monthly' => 8900, 'annual' => 99900],
    ];

    // tinyint code stored in profile_business.membership_plan etc.
    // 'basic' and 'platinum' are only ever set by an admin (see grantPlan());
    // the public pricing page only self-serves premium/gold via Razorpay.
    public const PLAN_CODES = [
        'premium' => 1,
        'gold' => 2,
        'basic' => 3,
        'platinum' => 4,
    ];

    // smallint stored in profile_memberships.membership_type, joined against
    // membership_plans.plan_id purely to display a plan name on My Plan.
    public const PLAN_MEMBERSHIP_TYPE = [
        'premium' => 2,
        'gold' => 3,
        'basic' => 1,
        'platinum' => 4,
    ];

    // Validity used when an admin manually grants a plan (pricing page
    // "45 Days" / "3 Months" / "12 Months" / "12 Months" terms).
    public const PLAN_VALIDITY_DAYS = [
        'basic' => 45,
        'premium' => 90,
        'gold' => 365,
        'platinum' => 365,
    ];

    public const PROFILE_TYPE_LEGACY_CODES = [
        'business' => 1,
        'investor' => 2,
        'mentor' => 4,
        'startup' => 7,
    ];

    public const PROFILE_TABLES = [
        'business' => ['table' => 'profile_business', 'id' => 'business_id'],
        'investor' => ['table' => 'profile_investor', 'id' => 'investor_id'],
        'mentor' => ['table' => 'profile_mentors', 'id' => 'mentor_id'],
        'startup' => ['table' => 'profile_startups', 'id' => 'startup_id'],
    ];

    /**
     * Marks a payment as successful and, when it already belongs to a profile
     * (My Plan upgrade), activates the membership. Idempotent: a payment that
     * is already successful is returned untouched, so replaying a valid
     * signature or receiving the webhook after the browser callback cannot
     * extend the membership a second time.
     */
    public function complete(string $orderNo, string $razorpayPaymentId): ?object
    {
        return DB::transaction(function () use ($orderNo, $razorpayPaymentId): ?object {
            $payment = DB::table('online_payments')->where('order_no', $orderNo)->lockForUpdate()->first();

            if (! $payment || (int) $payment->payment_status === self::STATUS_SUCCESS) {
                return $payment;
            }

            DB::table('online_payments')->where('payment_id', $payment->payment_id)->update([
                'payment_status' => self::STATUS_SUCCESS,
                'razorpay_payment_id' => $razorpayPaymentId,
                'status_message' => 'razorpay_payment_id:'.$razorpayPaymentId,
                'updated_at' => now(),
            ]);

            $payment->payment_status = self::STATUS_SUCCESS;

            if ((int) $payment->profile_id > 0) {
                $this->activateProfile($payment);
            }

            return $payment;
        });
    }

    /**
     * A failed signature check must never downgrade a payment that has
     * already been verified (e.g. by the webhook).
     */
    public function markSignatureFailed(string $orderNo): void
    {
        DB::table('online_payments')
            ->where('order_no', $orderNo)
            ->where('payment_status', self::STATUS_INITIATED)
            ->update([
                'payment_status' => self::STATUS_FAILED,
                'status_message' => 'Signature verification failed.',
                'updated_at' => now(),
            ]);
    }

    /**
     * Credits a successful, unclaimed guest checkout to the profile that was
     * just created during registration. The caller must establish that the
     * registrant is the payer ($payerVerified): either the order was verified
     * in this same session, or the account email matches the checkout email.
     */
    public function claimForProfile(string $orderNo, string $profileType, int $profileId, int $userId, bool $payerVerified, string $accountEmail): bool
    {
        $legacyType = self::PROFILE_TYPE_LEGACY_CODES[$profileType] ?? null;
        $profileTable = self::PROFILE_TABLES[$profileType] ?? null;

        if ($legacyType === null || $profileTable === null) {
            return false;
        }

        return DB::transaction(function () use ($orderNo, $legacyType, $profileTable, $profileId, $userId, $payerVerified, $accountEmail): bool {
            $payment = DB::table('online_payments')
                ->where('order_no', $orderNo)
                ->where('payment_status', self::STATUS_SUCCESS)
                ->where('profile_id', 0)
                ->where('profile_type', $legacyType)
                ->lockForUpdate()
                ->first();

            if (! $payment) {
                return false;
            }

            if (! $payerVerified && strcasecmp(trim((string) $payment->email), trim($accountEmail)) !== 0) {
                return false;
            }

            $planCode = self::PLAN_CODES[$payment->membership_plan] ?? null;
            if ($planCode === null) {
                return false;
            }

            $claimed = DB::table('online_payments')
                ->where('payment_id', $payment->payment_id)
                ->where('profile_id', 0)
                ->update([
                    'profile_id' => $profileId,
                    'user_id' => $userId,
                    'updated_at' => now(),
                ]);

            if ($claimed !== 1) {
                return false;
            }

            DB::table($profileTable['table'])->where($profileTable['id'], $profileId)->update([
                'membership_paid' => 1,
                'membership_plan' => $planCode,
                'updated_at' => now(),
            ]);
            PublicContentCache::forgetProfileContent();

            return true;
        });
    }

    public function profileTypeFor(object $payment): ?string
    {
        $type = array_search((int) $payment->profile_type, self::PROFILE_TYPE_LEGACY_CODES, true);

        return $type === false ? null : $type;
    }

    public function expectedAmountInPence(object $payment): int
    {
        return (int) round(((float) $payment->amount) * 100);
    }

    /**
     * Admin-side equivalent of a successful Razorpay payment: activates a
     * membership plan on a profile without any payment ever taking place.
     * Supports all four pricing-page tiers (basic/premium/gold/platinum),
     * not just the two the public "My Plan" checkout can sell.
     */
    public function grantPlan(string $profileType, int $profileId, int $userId, string $plan, int $adminId): bool
    {
        $profileTable = self::PROFILE_TABLES[$profileType] ?? null;
        $legacyType = self::PROFILE_TYPE_LEGACY_CODES[$profileType] ?? null;
        $planCode = self::PLAN_CODES[$plan] ?? null;

        if ($profileTable === null || $legacyType === null || $planCode === null) {
            return false;
        }

        DB::transaction(function () use ($profileTable, $legacyType, $profileType, $profileId, $userId, $plan, $planCode, $adminId): void {
            DB::table($profileTable['table'])->where($profileTable['id'], $profileId)->update([
                'membership_paid' => 1,
                'membership_plan' => $planCode,
                'updated_at' => now(),
            ]);

            DB::table('profile_memberships')
                ->where('user_id', $userId)
                ->where('profile_type', $legacyType)
                ->where('profile_id', $profileId)
                ->where('is_active', 1)
                ->update(['is_active' => 0, 'updated_at' => now()]);

            DB::table('profile_memberships')->insert([
                'user_id' => $userId,
                'profile_type' => $legacyType,
                'profile_id' => $profileId,
                'order_no' => 0,
                'amount' => '0',
                'membership_type' => self::PLAN_MEMBERSHIP_TYPE[$plan] ?? 1,
                'payment_source' => 2,
                'payment_comments' => ucfirst($plan).' Plan (granted by admin #'.$adminId.')',
                'interaction_credits' => 0,
                'instant_responses' => 0,
                'activation_date' => now(),
                'expiry_date' => now()->addDays(self::PLAN_VALIDITY_DAYS[$plan] ?? 30),
                'is_active' => 1,
                'upg_source' => 2,
                'assigned_by' => $adminId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            PublicContentCache::forgetProfileContent();
        });

        return true;
    }

    private function activateProfile(object $payment): void
    {
        $profileType = $this->profileTypeFor($payment);
        $planCode = self::PLAN_CODES[$payment->membership_plan] ?? null;

        if ($profileType === null || $planCode === null) {
            return;
        }

        $profileTable = self::PROFILE_TABLES[$profileType];
        DB::table($profileTable['table'])->where($profileTable['id'], $payment->profile_id)->update([
            'membership_paid' => 1,
            'membership_plan' => $planCode,
            'updated_at' => now(),
        ]);

        $this->recordMembership($payment);
        PublicContentCache::forgetProfileContent();
    }

    /**
     * Writes the row the My Plan page's "Membership Details" table actually
     * reads from (profile_memberships) — separate from the membership_paid
     * flag on the profile row itself, which only gates what the directory
     * listing shows.
     */
    private function recordMembership(object $payment): void
    {
        DB::table('profile_memberships')
            ->where('user_id', $payment->user_id)
            ->where('profile_type', $payment->profile_type)
            ->where('profile_id', $payment->profile_id)
            ->where('is_active', 1)
            ->update(['is_active' => 0, 'updated_at' => now()]);

        $isAnnual = $this->expectedAmountInPence($payment) === (self::PLAN_AMOUNTS[$payment->membership_plan]['annual'] ?? null);

        // membership_plans.plan_id, joined by the My Plan page purely to
        // display a plan_name — plan 2/3 happen to be named "Premium
        // Plan"/"Gold Plan" there, which is what we want shown either way.
        $membershipPlanId = self::PLAN_MEMBERSHIP_TYPE[$payment->membership_plan] ?? 2;

        DB::table('profile_memberships')->insert([
            'user_id' => $payment->user_id,
            'profile_type' => $payment->profile_type,
            'profile_id' => $payment->profile_id,
            // profile_memberships.order_no is a numeric FK to
            // online_payments.payment_id — not the alphanumeric order_no
            // string (e.g. "BX261013...") used everywhere else.
            'order_no' => $payment->payment_id,
            'amount' => $payment->amount,
            'membership_type' => $membershipPlanId,
            // payment_source/upg_source are tinyint codes with no other
            // reference in this codebase (legacy schema) — 1 reads as
            // "online payment" / "self-service upgrade" respectively.
            'payment_source' => 1,
            'payment_comments' => ucfirst($payment->membership_plan).' Plan',
            'interaction_credits' => 0,
            'instant_responses' => 0,
            'activation_date' => now(),
            'expiry_date' => $isAnnual ? now()->addYear() : now()->addMonth(),
            'is_active' => 1,
            'upg_source' => 1,
            'assigned_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
