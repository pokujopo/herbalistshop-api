<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class AzamPayService
{
    private function getToken()
    {
        $response = Http::post('https://authenticator-sandbox.azampay.co.tz/AppRegistration/GenerateToken', [
            'appName' => config('services.azampay.app_name'),
            'clientId' => config('services.azampay.client_id'),
            'clientSecret' => config('services.azampay.client_secret'),
        ]);

        if (!$response->successful()) {
            throw new \Exception('Failed to get AzamPay token: ' . $response->body());
        }

        return $response->json()['data']['accessToken'];
    }

    public function initiate(array $payload): array
    {
        /** @var Payment $payment */
        $payment = $payload['payment'];

        /** @var Order $order */
        $order = $payload['order'];

        $token = $this->getToken();

        $response = Http::withToken($token)
            ->post(config('services.azampay.base_url') . '/api/v1/Partner/PostCheckout', [
                "merchantAccountNumber" => "1234567890", // weka yako
                "amount" => $payment->amount,
                "currency" => "TZS",
                "provider" => strtoupper($payment->payment_method), // MPESA / MIXX
                "externalId" => $order->order_number,
                "customerPhoneNumber" => $payment->phone,
                "additionalProperties" => [
                    "note" => "Order payment"
                ]
            ]);

        if (!$response->successful()) {
            return [
                'status' => 'failed',
                'message' => 'Payment request failed' . $response->body(),
                'raw' => $response->body(),
            ];
        }

        $data = $response->json();

        return [
            'status' => 'pending',
            'message' => 'Payment request sent to phone',
            'provider_reference' => $data['data']['checkoutRequestId'] ?? null,
            'transaction_reference' => $order->order_number,
            'checkout_url' => $data['data']['paymentUrl'] ?? null,
        ];
    }

    public function handleWebhook(Request $request)
    {
        $payload = $request->all();

        $externalId = $payload['externalId'] ?? null;
        $status = $payload['status'] ?? null;

        $payment = Payment::where('transaction_reference', $externalId)->first();

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $payment->raw_response = json_encode($payload);

        if ($status === 'SUCCESS') {
            $payment->status = 'paid';
            $payment->paid_at = now();
            $payment->save();

            $order = $payment->order;
            $order->payment_status = 'paid';
            $order->order_status = 'processing';
            $order->save();
        } elseif ($status === 'FAILED') {
            $payment->status = 'failed';
            $payment->save();
        }

        return response()->json(['success' => true]);
    }
}
