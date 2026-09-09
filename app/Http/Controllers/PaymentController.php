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

    public function webhook(Request $request): JsonResponse
{
    $payload = $request->all();

    Log::info('PalmPesa webhook received', [
        'payload' => $payload,
    ]);

    try {
        $data = data_get($payload, 'data.0', []);

        $palmOrderId =
            $data['order_id']
            ?? $payload['order_id']
            ?? null;

        $status =
            strtoupper(
                $data['payment_status']
                ?? $payload['payment_status']
                ?? ''
            );

        if (!$palmOrderId) {
            return response()->json([
                'success' => false,
                'message' => 'Missing order_id.',
            ], 400);
        }

        $order = Order::query()
            ->where('payment_reference', $palmOrderId)
            ->lockForUpdate()
            ->first();

        if (!$order) {
            Log::warning(
                'PalmPesa webhook order not found',
                [
                    'palm_order_id' => $palmOrderId,
                ]
            );

            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        DB::transaction(function () use (
            $order,
            $status,
            $data
        ) {

            if ($status === 'COMPLETED') {

                if ($order->payment_status !== 'paid') {

                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'processing',
                        'paid_at' => now(),
                        'transaction_id' =>
                            $data['transid'] ?? null,
                        'payment_channel' =>
                            $data['channel'] ?? null,
                    ]);

                    // IMPORTANT:
                    // Put stock reduction / email / invoice /
                    // notification logic here.
                }

                return;
            }

            if ($status === 'FAILED') {

                $order->update([
                    'payment_status' => 'failed',
                ]);

                return;
            }

            if ($status === 'PENDING') {

                $order->update([
                    'payment_status' => 'pending',
                ]);
            }
        });

        return response()->json([
            'success' => true,
        ]);

    } catch (Throwable $e) {

        Log::error(
            'PalmPesa webhook processing failed',
            [
                'error' => $e->getMessage(),
                'payload' => $payload,
            ]
        );

        return response()->json([
            'success' => false,
        ], 500);
    }
}
}
