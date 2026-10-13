<?php

namespace App\Services\Payments;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

/**
 * Thin wrapper around the Razorpay REST API.
 *
 * Orders are created through Laravel's HTTP client rather than the SDK because
 * the SDK hard-codes a 60s timeout, which would tie up a PHP worker for a full
 * minute whenever Razorpay is slow. Signatures are HMAC-SHA256 as documented
 * by Razorpay and compared in constant time.
 */
class RazorpayGateway
{
    private const API_BASE = 'https://api.razorpay.com/v1';

    public function isConfigured(): bool
    {
        return filled(config('services.razorpay.key')) && filled(config('services.razorpay.secret'));
    }

    public function key(): ?string
    {
        return config('services.razorpay.key');
    }

    /**
     * @return array{id: string}
     *
     * @throws ConnectionException|RequestException
     */
    public function createOrder(string $receipt, int $amountInMinorUnits, string $currency): array
    {
        return Http::withBasicAuth((string) config('services.razorpay.key'), (string) config('services.razorpay.secret'))
            ->acceptJson()
            ->connectTimeout(5)
            ->timeout(15)
            ->post(self::API_BASE.'/orders', [
                'receipt' => $receipt,
                'amount' => $amountInMinorUnits,
                'currency' => $currency,
                'payment_capture' => 1,
            ])
            ->throw()
            ->json();
    }

    public function isValidPaymentSignature(string $orderId, string $paymentId, string $signature): bool
    {
        return $this->signatureMatches($orderId.'|'.$paymentId, $signature, (string) config('services.razorpay.secret'));
    }

    public function isValidWebhookSignature(string $payload, string $signature): bool
    {
        return $this->signatureMatches($payload, $signature, (string) config('services.razorpay.webhook_secret'));
    }

    private function signatureMatches(string $payload, string $signature, string $secret): bool
    {
        if ($secret === '' || $signature === '') {
            return false;
        }

        return hash_equals(hash_hmac('sha256', $payload, $secret), $signature);
    }
}
