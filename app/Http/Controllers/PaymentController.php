<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    // ✅ 1. Get Access Token
    private function getAccessToken()
{
    $url = 'https://sandbox.azampay.co.tz/api/v1/Authentication/GenerateToken';

    $credentials = [
        'appName' => env('AZAMPAY_APP_NAME'),
        'clientId' => env('AZAMPAY_CLIENT_ID'),
        'clientSecret' => env('AZAMPAY_CLIENT_SECRET'),
    ];

    // Tuma request kwa kutumia JSON body
    $response = Http::withHeaders([
        'Content-Type' => 'application/json', // Muhimu: Weka Content-Type
    ])->post($url, $credentials); // Laravel itabadilisha array hii kuwa JSON

    if ($response->successful()) {
        // Angalia structure sahihi ya response. Inatakiwa kurejesha 'access_token' au 'accessToken'?
        return $response->json()['data']['accessToken'] ?? null; 
    }
    
    // Debugging: Toa ujumbe kamili wa kosa.
    if ($response->failed()) {
        logger('AzamPay Token Error: ' . $response->status());
        logger('AzamPay Response Body: ' . $response->body());
    }

    return null;
}

    // ✅ 2. Send Payment Request
    public function initiatePayment(Request $request)
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return response()->json(['error' => 'Failed to get access token'], 400);
        }

        $url = env('AZAMPAY_BASE_URL') . '/api/v1/Payment/RequestPayment';
        $payload = [
            'accountNumber' => $request->phone_number,   // e.g. 255712345678
            'amount' => $request->amount,                // e.g. 1000
            'currency' => 'TZS',
            'merchant' => env('AZAMPAY_MERCHANT_ID'),
            'externalReference' => 'ORDER-' . uniqid(),
            'callbackUrl' => env('AZAMPAY_CALLBACK_URL'),
        ];

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post($url, $payload);

        if ($response->successful()) {
            return response()->json([
                'message' => 'Payment request sent successfully',
                'data' => $response->json(),
            ]);
        } else {
            return response()->json([
                'error' => 'Failed to initiate payment',
                'response' => $response->json(),
            ], 400);
        }
    }

    // ✅ 3. Handle Callback (Webhook)
    public function handleCallback(Request $request)
    {
        Log::info('AzamPay Callback Received:', $request->all());

        // Example response:
        // {
        //   "status": "COMPLETED",
        //   "transactionId": "AZM123456",
        //   "amount": 1000
        // }

        if ($request->status === 'COMPLETED') {
            // Update order/payment record in DB
            // Example:
            // Payment::where('reference', $request->externalReference)
            //     ->update(['status' => 'completed']);
        }

        return response()->json(['message' => 'Callback received']);
    }
}
