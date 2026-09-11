<?php

namespace App\Services\Payments;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RuntimeException;

class PaymentManager
{
    public function __construct(
        private PalmPesaService $palmPesa
    ) {
    }

    /**
     * Start payment.
     */
    public function initiate(
        string $provider,
        array $data
    ): array {
        return match ($provider) {

            'palmpesa' =>
                $this->initiatePalmPesa($data),

            'cash' => [
                'status' => 'pending',
                'message' => 'Pay on delivery.',
            ],

            default => throw new RuntimeException(
                "Unsupported payment provider: {$provider}"
            ),
        };
    }

    private function initiatePalmPesa(
        array $data
    ): array {
        $payment = $data['payment'];
        $order = $data['order'];

        $user = $order->user;
        $address = $order->address;

        /*
         * Generate our own unique transaction ID.
         *
         * This is NOT PalmPesa's order_id.
         */
        $transactionId =
            $payment->transaction_reference
            ?? 'TXN-' . strtoupper(
                Str::random(16)
            );

        /*
         * Save our transaction ID BEFORE
         * contacting PalmPesa.
         */
        $payment->update([
            'transaction_reference' =>
                $transactionId,
        ]);

        return $this->palmPesa->initiate([
            'name' =>
                $address->full_name,

            'email' =>
                $user->email,

            'phone' =>
                $payment->phone,

            'amount' =>
                (int) $payment->amount,

            'transaction_id' =>
                $transactionId,

            'address' =>
                implode(', ', array_filter([
                    $address->street_address,
                    $address->city,
                    $address->region,
                ])),

            /*
             * PalmPesa requires postcode.
             *
             * UI does not currently collect it,
             * so keep it in backend config.
             */
            'postcode' =>
                config(
                    'services.palmpesa.postcode',
                    '00000'
                ),

            /*
             * IMPORTANT:
             * This is the webhook URL PalmPesa
             * will call after payment status changes.
             */
            'callback_url' =>
                config(
                    'services.palmpesa.callback_url'
                ),
        ]);
    }

    /**
     * Handle PalmPesa webhook.
     */
    public function handleWebhook(
        string $provider,
        Request $request
    ) {
        return match ($provider) {

            'palmpesa' => app(
                PalmPesaWebhookService::class
            )->handle($request),

            default => response()->json([
                'success' => false,
                'message' =>
                    "Unsupported provider: {$provider}",
            ], 400),
        };
    }
}
