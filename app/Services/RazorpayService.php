<?php

namespace App\Services;

use App\Models\Donation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class RazorpayService
{
    public function __construct(
        private readonly string $key,
        private readonly string $secret,
        private readonly string $currency = 'INR',
    ) {}

    public function isConfigured(): bool
    {
        return $this->key !== ''
            && $this->secret !== ''
            && ! str_contains($this->key, 'placeholder')
            && ! str_contains($this->secret, 'placeholder');
    }

    /**
     * Create a Razorpay order. When credentials are placeholders (local dev),
     * return a fake order id so the donation flow can be exercised end-to-end.
     */
    public function createOrder(Donation $donation): array
    {
        if (! $this->isConfigured()) {
            return [
                'id' => 'order_LOCAL_'.Str::upper(Str::random(14)),
                'amount' => $donation->amount * 100,
                'currency' => $this->currency,
                'status' => 'created',
                'simulated' => true,
            ];
        }

        $response = Http::withBasicAuth($this->key, $this->secret)
            ->acceptJson()
            ->post('https://api.razorpay.com/v1/orders', [
                'amount' => $donation->amount * 100, // paise
                'currency' => $this->currency,
                'receipt' => $donation->reference_no,
                'notes' => [
                    'donor_name' => $donation->donor_name,
                    'donor_email' => $donation->donor_email,
                ],
            ]);

        $response->throw();
        return $response->json();
    }

    public function verifySignature(string $orderId, string $paymentId, string $signature): bool
    {
        if (! $this->isConfigured()) {
            // local/dev: trust simulated callbacks
            return true;
        }

        $expected = hash_hmac('sha256', $orderId.'|'.$paymentId, $this->secret);

        return hash_equals($expected, $signature);
    }
}
