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
     * PalmPesa Pay via Mobile / USSD
     *
     * This does NOT create a hosted checkout.
     * It sends a mobile-money push directly to customer's phone.
     */
    public function payViaMobile(array $data): array
    {
        $response = $this->client()->post('/api/pay-via-mobile', [
            'user_id' => (string) config('services.palmpesa.user_id'),

            'name' => $data['name'],

            'email' => $data['email'],

            'phone' => $this->normalizePhone(
                $data['phone']
            ),

            'amount' => (int) $data['amount'],

            'transaction_id' => $data['transaction_id'],

            'address' => $data['address'],

            'postcode' => $data['postcode'],

            'buyer_uuid' => (int) $data['buyer_uuid'],
        ]);

        if ($response->failed()) {
            throw new RuntimeException(
                'PalmPesa payment request failed: ' .
                $response->body()
            );
        }

        $json = $response->json();

        return [
            'status' => $this->mapStatus($json),

            'message' => $json['message']
                ?? $json['response']['message']
                ?? 'Payment request sent to your phone.',

            /*
             * PalmPesa's own order ID.
             * Example: SELCOM17458294939723
             */
            'provider_reference' =>
                $json['order_id']
                ?? $json['response']['reference']
                ?? null,

            /*
             * Our transaction ID is preserved.
             */
            'transaction_reference' =>
                $data['transaction_id'],

            'raw' => $json,
        ];
    }

    /**
     * Optional PalmPesa order-status endpoint.
     *
     * Useful as a fallback if webhook delivery is delayed.
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

    private function mapStatus(array $response): string
    {
        $resultCode =
            $response['response']['resultcode']
            ?? $response['resultcode']
            ?? null;

        $result =
            strtoupper(
                $response['response']['result']
                ?? $response['result']
                ?? ''
            );

        /*
         * 000 / SUCCESS means the push request was accepted.
         * Customer still needs to enter PIN.
         */
        if ($resultCode === '000' && $result === 'SUCCESS') {
            return 'pending';
        }

        return 'failed';
    }

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
