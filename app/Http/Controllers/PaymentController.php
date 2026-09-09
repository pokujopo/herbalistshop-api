<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Payments\PalmPesaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class PaymentController extends Controller
{
    public function __construct(
        private readonly PalmPesaService $palmPesa
    ) {
    }

    /**
     * Start PalmPesa checkout.
     */
    public function initiate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => [
                'required',
                'integer',
                'exists:orders,id',
            ],
        ]);

        $order = Order::query()
            ->with('user')
            ->findOrFail($validated['order_id']);

        if (
            $order->payment_status === 'paid'
        ) {
            return response()->json([
                'success' => false,
                'message' => 'This order has already been paid.',
            ], 422);
        }

        $paymentOrderId = 'ORD-' . strtoupper(
            Str::random(10)
        );

        try {
            $response = $this->palmPesa->createPayment([
                'order_id' => $paymentOrderId,

                'buyer_name' => $order->user?->name
                    ?? $order->customer_name,

                'buyer_email' => $order->user?->email
                    ?? $order->customer_email,

                'buyer_phone' => $order->customer_phone,

                'amount' => $order->total,

                'no_of_items' => $order->items()->count(),

                'buyer_remarks' =>
                    'Herbalist Order ' . $order->order_number,

                'merchant_remarks' =>
                    'Herbal Products Order',
            ]);

            $checkoutUrl =
                data_get(
                    $response,
                    'raw.payment_gateway_url'
                );

            if (!$checkoutUrl) {
                Log::error(
                    'PalmPesa missing checkout URL',
                    [
                        'order_id' => $order->id,
                        'response' => $response,
                    ]
                );

                return response()->json([
                    'success' => false,
                    'message' =>
                        'PalmPesa did not return a checkout URL.',
                ], 502);
            }

            $order->update([
                'payment_status' => 'pending',
                'payment_provider' => 'palmpesa',
                'payment_reference' => $paymentOrderId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment initialized.',
                'order_number' => $order->order_number,
                'payment_status' => 'pending',
                'checkout_url' => $checkoutUrl,
                'transaction_reference' => $paymentOrderId,
            ]);

        } catch (Throwable $e) {

            Log::error(
                'PalmPesa payment initialization failed',
                [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' =>
                    'Unable to initialize payment.',
            ], 502);
        }
    }
}
