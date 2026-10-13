<?php

namespace Tests\Feature;

use App\Models\UserAccount;
use App\Services\Payments\MembershipPaymentService;
use App\Support\PublicContentCache;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class MembershipPaymentTest extends TestCase
{
    private const SECRET = 'test_key_secret';

    private const WEBHOOK_SECRET = 'test_webhook_secret';

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.razorpay.key' => 'rzp_test_key',
            'services.razorpay.secret' => self::SECRET,
            'services.razorpay.webhook_secret' => self::WEBHOOK_SECRET,
        ]);

        $this->artisan('migrate', [
            '--path' => [
                'database/migrations/2026_08_04_093812_000064_create_online_payments_table.php',
                'database/migrations/2026_08_04_093812_000073_create_profile_memberships_table.php',
                'database/migrations/2026_10_13_000001_harden_online_payments_and_memberships.php',
            ],
        ])->assertSuccessful();

        Schema::create('user_account', function (Blueprint $table): void {
            $table->increments('user_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('mobile')->nullable();
            $table->string('company_name')->default('');
            $table->boolean('is_active')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('profile_business', function (Blueprint $table): void {
            $table->increments('business_id');
            $table->unsignedInteger('user_id');
            $table->tinyInteger('membership_paid')->default(0);
            $table->tinyInteger('membership_plan')->default(0);
            $table->timestamps();
        });
    }

    public function test_upgrade_verification_activates_membership_once_even_when_replayed(): void
    {
        [$user, $profileId] = $this->userWithBusinessProfile();
        $this->paymentRow(['order_no' => 'BXUPGRADE1', 'udf' => 'order_1', 'user_id' => $user->user_id, 'profile_id' => $profileId]);

        $payload = $this->signedCallback('BXUPGRADE1', 'order_1', 'pay_1');
        Cache::put(PublicContentCache::HOME_KEY, ['stale' => true]);

        $this->actingAs($user)->postJson(route('dashboard.my-plan.payment.verify'), $payload)
            ->assertOk()
            ->assertJson(['success' => true]);
        $this->assertFalse(Cache::has(PublicContentCache::HOME_KEY));

        $membership = DB::table('profile_memberships')->sole();
        $this->assertSame(1, (int) $membership->is_active);
        $this->assertSame(1, (int) DB::table('profile_business')->value('membership_paid'));
        $this->assertSame('pay_1', DB::table('online_payments')->value('razorpay_payment_id'));

        $this->travel(10)->days();

        $this->actingAs($user)->postJson(route('dashboard.my-plan.payment.verify'), $payload)->assertOk();

        $this->assertSame(1, DB::table('profile_memberships')->count());
        $this->assertSame((string) $membership->expiry_date, (string) DB::table('profile_memberships')->value('expiry_date'));
    }

    public function test_upgrade_verification_rejects_other_users_orders(): void
    {
        [$owner, $profileId] = $this->userWithBusinessProfile();
        [$intruder] = $this->userWithBusinessProfile('intruder@example.com');
        $this->paymentRow(['order_no' => 'BXOWNED', 'udf' => 'order_owned', 'user_id' => $owner->user_id, 'profile_id' => $profileId]);

        $this->actingAs($intruder)
            ->postJson(route('dashboard.my-plan.payment.verify'), $this->signedCallback('BXOWNED', 'order_owned', 'pay_x'))
            ->assertNotFound();

        $this->assertSame(MembershipPaymentService::STATUS_INITIATED, (int) DB::table('online_payments')->value('payment_status'));
    }

    public function test_invalid_signature_fails_pending_payment_but_never_downgrades_a_paid_one(): void
    {
        $this->paymentRow(['order_no' => 'BXPENDING', 'udf' => 'order_p']);
        $this->paymentRow(['order_no' => 'BXPAID', 'udf' => 'order_s', 'payment_status' => MembershipPaymentService::STATUS_SUCCESS, 'razorpay_payment_id' => 'pay_s']);

        foreach ([['BXPENDING', 'order_p'], ['BXPAID', 'order_s']] as [$orderNo, $orderId]) {
            $this->postJson(route('pricing.payment.verify'), [
                'order_no' => $orderNo,
                'razorpay_order_id' => $orderId,
                'razorpay_payment_id' => 'pay_forged',
                'razorpay_signature' => 'not-a-valid-signature',
            ])->assertStatus(422);
        }

        $this->assertSame(MembershipPaymentService::STATUS_FAILED, (int) DB::table('online_payments')->where('order_no', 'BXPENDING')->value('payment_status'));
        $this->assertSame(MembershipPaymentService::STATUS_SUCCESS, (int) DB::table('online_payments')->where('order_no', 'BXPAID')->value('payment_status'));
    }

    public function test_guest_checkout_can_only_be_claimed_once_by_the_verified_payer(): void
    {
        $this->paymentRow(['order_no' => 'BXGUEST', 'udf' => 'order_g', 'email' => 'payer@example.com']);

        $this->postJson(route('pricing.payment.verify'), $this->signedCallback('BXGUEST', 'order_g', 'pay_g'))
            ->assertOk()
            ->assertSessionHas('payments.verified_orders', ['BXGUEST']);

        $service = app(MembershipPaymentService::class);
        [, $strangerProfile] = $this->userWithBusinessProfile('stranger@example.com');
        [, $payerProfile] = $this->userWithBusinessProfile('payer@example.com');

        $this->assertFalse($service->claimForProfile('BXGUEST', 'business', $strangerProfile, 99, false, 'stranger@example.com'));
        $this->assertTrue($service->claimForProfile('BXGUEST', 'business', $payerProfile, 2, false, 'PAYER@example.com'));
        $this->assertFalse($service->claimForProfile('BXGUEST', 'business', $strangerProfile, 99, true, 'stranger@example.com'));

        $this->assertSame($payerProfile, (int) DB::table('online_payments')->value('profile_id'));
        $this->assertSame(0, (int) DB::table('profile_business')->where('business_id', $strangerProfile)->value('membership_paid'));
        $this->assertSame(1, (int) DB::table('profile_business')->where('business_id', $payerProfile)->value('membership_paid'));
    }

    public function test_webhook_requires_a_valid_signature(): void
    {
        $this->call('POST', route('webhooks.razorpay'), [], [], [], [
            'HTTP_X_RAZORPAY_SIGNATURE' => 'bogus',
            'CONTENT_TYPE' => 'application/json',
        ], '{"event":"payment.captured"}')->assertStatus(400);

        config(['services.razorpay.webhook_secret' => null]);

        $this->postJson(route('webhooks.razorpay'), [])->assertStatus(503);
    }

    public function test_captured_webhook_activates_upgrade_and_is_idempotent(): void
    {
        [$user, $profileId] = $this->userWithBusinessProfile();
        $this->paymentRow(['order_no' => 'BXHOOK', 'udf' => 'order_h', 'user_id' => $user->user_id, 'profile_id' => $profileId]);

        $body = $this->webhookBody('order_h', 'pay_h', 2900);

        $this->sendWebhook($body)->assertOk();
        $this->sendWebhook($body)->assertOk();

        $this->assertSame(MembershipPaymentService::STATUS_SUCCESS, (int) DB::table('online_payments')->value('payment_status'));
        $this->assertSame(1, DB::table('profile_memberships')->count());

        // The browser callback arriving after the webhook must not re-apply it.
        $this->actingAs($user)
            ->postJson(route('dashboard.my-plan.payment.verify'), $this->signedCallback('BXHOOK', 'order_h', 'pay_h'))
            ->assertOk();

        $this->assertSame(1, DB::table('profile_memberships')->count());
    }

    public function test_webhook_with_mismatched_amount_is_not_applied(): void
    {
        $this->paymentRow(['order_no' => 'BXCHEAP', 'udf' => 'order_c']);

        $this->sendWebhook($this->webhookBody('order_c', 'pay_c', 100))->assertOk();

        $this->assertSame(MembershipPaymentService::STATUS_INITIATED, (int) DB::table('online_payments')->value('payment_status'));
    }

    public function test_initiate_creates_a_server_priced_order(): void
    {
        Http::fake(['api.razorpay.com/*' => Http::response(['id' => 'order_new'])]);

        $this->postJson(route('pricing.payment.initiate'), [
            'selected_plan' => 'gold',
            'billing_period' => 'annual',
            'profile_type' => 'business',
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'mobile' => '07000000000',
            'company' => 'Acme',
            'payment_mode' => 'online',
        ])->assertOk()->assertJson(['order_id' => 'order_new', 'amount' => 99900, 'currency' => 'GBP']);

        Http::assertSent(fn ($request) => $request['amount'] === 99900 && $request['currency'] === 'GBP');
        $this->assertSame('999.00', DB::table('online_payments')->value('amount'));
    }

    /**
     * @return array{0: UserAccount, 1: int}
     */
    private function userWithBusinessProfile(string $email = 'owner@example.com'): array
    {
        $userId = DB::table('user_account')->insertGetId([
            'name' => 'Owner',
            'email' => $email,
            'company_name' => 'Acme',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $profileId = DB::table('profile_business')->insertGetId([
            'user_id' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return [UserAccount::query()->findOrFail($userId), $profileId];
    }

    private function paymentRow(array $overrides): void
    {
        DB::table('online_payments')->insert($overrides + [
            'user_id' => 0,
            'profile_type' => MembershipPaymentService::PROFILE_TYPE_LEGACY_CODES['business'],
            'profile_id' => 0,
            'name' => 'Payer',
            'email' => 'payer@example.com',
            'phone' => '07000000000',
            'city' => '',
            'product_details' => 'Premium Business Membership (monthly)',
            'membership_plan' => 'premium',
            'amount' => '29.00',
            'payment_status' => MembershipPaymentService::STATUS_INITIATED,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function signedCallback(string $orderNo, string $orderId, string $paymentId): array
    {
        return [
            'order_no' => $orderNo,
            'razorpay_order_id' => $orderId,
            'razorpay_payment_id' => $paymentId,
            'razorpay_signature' => hash_hmac('sha256', $orderId.'|'.$paymentId, self::SECRET),
        ];
    }

    private function webhookBody(string $orderId, string $paymentId, int $amount): string
    {
        return json_encode([
            'event' => 'payment.captured',
            'payload' => ['payment' => ['entity' => [
                'id' => $paymentId,
                'order_id' => $orderId,
                'amount' => $amount,
                'currency' => 'GBP',
            ]]],
        ]);
    }

    private function sendWebhook(string $body): TestResponse
    {
        return $this->call('POST', route('webhooks.razorpay'), [], [], [], [
            'HTTP_X_RAZORPAY_SIGNATURE' => hash_hmac('sha256', $body, self::WEBHOOK_SECRET),
            'CONTENT_TYPE' => 'application/json',
        ], $body);
    }
}
