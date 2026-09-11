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
     * Initiate direct mobile-money payment.
     *
     * PalmPesa sends a payment prompt to the customer's phone.
     */
    public function initiate(array $data): array
    {
        $response = $this->client()->post(
            '/api/palmpesa/initiate',
            [
                'name' => $data['name'],

                'email' => $data['email'],

                'phone' => $this->normalizePhone(
                    $data['phone']
                ),

                'amount' => (int) $data['amount'],

                'transaction_id' =>
                    $data['transaction_id'],

                'address' =>
                    $data['address'],

                'postcode' =>
                    $data['postcode'],

                'callback_url' =>
                    $data['callback_url'],
            ]
        );

        if ($response->failed()) {
            throw new RuntimeException(
                'PalmPesa payment request failed: ' .
                $response->body()
            );
        }

        $json = $response->json();

        /*
         * PalmPesa /initiate returns:
         *
         * {
         *   "message": "...",
         *   "order_id": "PALMPESA..."
         * }
         *
         * IMPORTANT:
         * This means the payment request was initiated.
         * It does NOT mean the customer has paid.
         */
        $providerOrderId =
            $json['order_id'] ?? null;

        if (!$providerOrderId) {
            throw new RuntimeException(
                'PalmPesa did not return an order_id.'
            );
        }

        return [
            'status' => 'pending',

            'message' =>
                $json['message']
                ?? 'Payment request sent to your phone.',

            /*
             * PalmPesa order ID.
             */
            'provider_reference' =>
                $providerOrderId,

            /*
             * Our transaction ID.
             */
            'transaction_reference' =>
                $data['transaction_id'],

            'raw' => $json,
        ];
    }

    /**
     * Check PalmPesa order status.
     *
     * This can be used as a fallback if webhook
     * delivery is delayed.
     */
    public function getOrderStatus(
        string $orderId
    ): array {
        $response = $this->client()->post(
            '/api/order-status',
            [
                'order_id' => $orderId,
            ]
        );

        if ($response->failed()) {
            throw new RuntimeException(
                'PalmPesa status request failed: ' .
                $response->body()
            );
        }

        return $response->json();
    }

    private function normalizePhone(
        string $phone
    ): string {
        $phone = preg_replace(
            '/\D+/',
            '',
            $phone
        );

        if (str_starts_with($phone, '255')) {
            return $phone;
        }

        if (str_starts_with($phone, '0')) {
            return '255' . substr($phone, 1);
        }

        return $phone;
    }
}
