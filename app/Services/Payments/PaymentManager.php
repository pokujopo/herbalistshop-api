<?php

namespace App\Services\Payments;

use Illuminate\Http\Request;
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
    public function initiate(string $provider, array $data): array
    {
        return match ($provider) {
            'palmpesa' => $this->initiatePalmPesa($data),

            'cash' => [
                'status' => 'pending',
                'message' => 'Pay on delivery.',
            ],

            default => throw new RuntimeException(
                "Unsupported payment provider: {$provider}"
            ),
        };
    }

    private function initiatePalmPesa(array $data): array
    {
        $payment = $data['payment'];
        $order = $data['order'];

        $user = $order->user;

        $transactionId =
            $payment->transaction_reference
            ?? 'TXN-' . strtoupper(
                \Illuminate\Support\Str::random(16)
            );

        /*
         * Save our transaction ID before sending request.
         */
        $payment->update([
            'transaction_reference' => $transactionId,
        ]);

        $address = $order->address;

        return $this->palmPesa->payViaMobile([
            'name' => $address->full_name,

            'email' => $user->email,

            'phone' => $payment->phone,

            'amount' => $payment->amount,

            'transaction_id' => $transactionId,

            'address' => implode(', ', array_filter([
                $address->street_address,
                $address->city,
                $address->region,
            ])),

            /*
             * PalmPesa requires postcode but your current
             * checkout UI doesn't ask for one.
             *
             * Therefore keep it in backend configuration.
             */
            'postcode' => config(
                'services.palmpesa.postcode',
                '00000'
            ),

            /*
             * Unique buyer ID in our system.
             */
            'buyer_uuid' => $user->id,
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
                'message' => "Unsupported provider: {$provider}",
            ], 400),
        };
    }
}
