<?php

namespace App\Services\Payments;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class PalmPesaService
{
    private function client(): PendingRequest
    {
        return Http::baseUrl(
            rtrim(config('services.palmpesa.base_url'), '/')
        )
            ->withToken(config('services.palmpesa.api_token'))
            ->acceptJson()
            ->asJson()
            ->timeout(30)
            ->connectTimeout(10);
    }

    /**
     * Create PalmPesa hosted checkout.
     */
    public function createPayment(array $data): array
    {
        $response = $this->client()->post('/api/process-payment', [
            'user_id' => (int) config('services.palmpesa.user_id'),

            'vendor' => config('services.palmpesa.vendor'),

            'order_id' => $data['order_id'],

            'buyer_email' => $data['buyer_email'],

            'buyer_name' => $data['buyer_name'],

            'buyer_phone' => $this->normalizePhone(
                $data['buyer_phone']
            ),

            'amount' => (int) $data['amount'],

            'currency' => 'TZS',

            'redirect_url' => config(
                'services.palmpesa.redirect_url'
            ),

            'cancel_url' => config(
                'services.palmpesa.cancel_url'
            ),

            'webhook' => config(
                'services.palmpesa.webhook_url'
            ),

            'buyer_remarks' => $data['buyer_remarks']
                ?? 'Herbalist Online Order',

            'merchant_remarks' => $data['merchant_remarks']
                ?? 'Herbal Products',

            'no_of_items' => (int) (
                $data['no_of_items'] ?? 1
            ),
        ]);

        if ($response->failed()) {
            throw new RuntimeException(
                'PalmPesa payment request failed: ' .
                $response->body()
            );
        }

        return $response->json();
    }

    /**
     * Check PalmPesa order status.
     */
    public function getOrderStatus(string $orderId): array
    {
        $response = $this->client()->post('/api/order-status', [
            'order_id' => $orderId,
        ]);

        if ($response->failed()) {
            throw new RuntimeException(
                'PalmPesa status request failed: ' .
                $response->body()
            );
        }

        return $response->json();
    }

    /**
     * Normalize Tanzanian phone numbers.
     */
    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D+/', '', $phone);

        if (str_starts_with($phone, '255')) {
            return $phone;
        }

        if (str_starts_with($phone, '0')) {
            return '255' . substr($phone, 1);
        }

        return $phone;
    }
}
