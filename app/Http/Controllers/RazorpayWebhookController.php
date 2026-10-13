<?php

namespace App\Http\Controllers;

use App\Services\Payments\MembershipPaymentService;
use App\Services\Payments\RazorpayGateway;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Server-to-server confirmation from Razorpay. Covers payments that were
 * captured but whose browser callback never reached us (tab closed, network
 * drop), so the customer is not charged without their plan being activated.
 *
 * CSRF does not apply (Razorpay cannot send a token); authenticity is proven
 * by the HMAC signature over the raw body instead.
 */
class RazorpayWebhookController extends Controller
{
    private const HANDLED_EVENTS = ['payment.captured', 'order.paid'];

    public function __construct(
        private readonly RazorpayGateway $gateway,
        private readonly MembershipPaymentService $payments,
    ) {}

    public function __invoke(Request $request): Response
    {
        if (blank(config('services.razorpay.webhook_secret'))) {
            return response('Webhook not configured.', 503);
        }

        if (! $this->gateway->isValidWebhookSignature($request->getContent(), (string) $request->header('X-Razorpay-Signature'))) {
            return response('Invalid signature.', 400);
        }

        if (! in_array($request->json('event'), self::HANDLED_EVENTS, true)) {
            return response('Ignored.', 200);
        }

        $entity = (array) $request->json('payload.payment.entity', []);
        $orderId = (string) ($entity['order_id'] ?? '');
        $paymentId = (string) ($entity['id'] ?? '');

        if ($orderId === '' || $paymentId === '') {
            return response('Ignored.', 200);
        }

        $payment = DB::table('online_payments')->where('udf', $orderId)->first();
        if (! $payment) {
            Log::info('Razorpay webhook for unknown order.', ['razorpay_order_id' => $orderId]);

            return response('Ignored.', 200);
        }

        if ((int) ($entity['amount'] ?? -1) !== $this->payments->expectedAmountInPence($payment)
            || strtoupper((string) ($entity['currency'] ?? '')) !== MembershipPaymentService::CURRENCY) {
            Log::warning('Razorpay webhook amount/currency mismatch; payment not applied.', [
                'order_no' => $payment->order_no,
                'razorpay_payment_id' => $paymentId,
            ]);

            return response('Amount mismatch.', 200);
        }

        $this->payments->complete($payment->order_no, $paymentId);

        return response('OK', 200);
    }
}
