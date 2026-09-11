<?php

namespace App\Services\Payments;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PalmPesaWebhookService
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        Log::info(
            'PalmPesa webhook received',
            [
                'payload' => $payload,
            ]
        );

        /*
         * PalmPesa callback:
         *
         * {
         *   "order_id": "PALMPESA...",
         *   "payment_status": "COMPLETED"
         * }
         */

        $providerReference =
            $payload['order_id']
            ?? null;

        $status = strtoupper(
            $payload['payment_status']
            ?? ''
        );

        if (!$providerReference) {
            Log::warning(
                'PalmPesa webhook missing order_id',
                [
                    'payload' => $payload,
                ]
            );

            return response()->json([
                'success' => false,
                'message' =>
                    'order_id is required.',
            ], 400);
        }

        if (!in_array(
            $status,
            [
                'PENDING',
                'COMPLETED',
                'FAILED',
            ],
            true
        )) {
            Log::warning(
                'PalmPesa webhook unknown status',
                [
                    'status' => $status,
                    'payload' => $payload,
                ]
            );

            return response()->json([
                'success' => false,
                'message' =>
                    'Unknown payment status.',
            ], 422);
        }

        /*
         * Find payment using PalmPesa order ID.
         */
        $payment = Payment::where(
            'provider_reference',
            $providerReference
        )->first();

        if (!$payment) {

            Log::warning(
                'PalmPesa payment not found',
                [
                    'provider_reference' =>
                        $providerReference,
                    'payload' =>
                        $payload,
                ]
            );

            /*
             * Return 200 so provider does not
             * keep retrying an unknown payment.
             */
            return response()->json([
                'success' => false,
                'message' =>
                    'Payment not found.',
            ], 200);
        }

        /*
         * Idempotency:
         *
         * If already paid, do nothing.
         */
        if (
            $payment->status === 'paid'
        ) {
            return response()->json([
                'success' => true,
                'message' =>
                    'Payment already processed.',
            ]);
        }

        /*
         * PENDING
         */
        if ($status === 'PENDING') {

            $payment->update([
                'status' => 'pending',

                'message' =>
                    'Payment is still pending.',

                'raw_response' =>
                    json_encode(
                        $payload,
                        JSON_UNESCAPED_SLASHES
                    ),
            ]);

            return response()->json([
                'success' => true,
                'message' =>
                    'Payment still pending.',
            ]);
        }

        DB::transaction(
            function () use (
                $payment,
                $status,
                $payload
            ) {

                /*
                 * Lock payment.
                 */
                $payment = Payment::lockForUpdate()
                    ->find($payment->id);

                /*
                 * Another webhook may have
                 * completed it while this one
                 * was waiting.
                 */
                if (
                    $payment->status === 'paid'
                ) {
                    return;
                }

                $order = Order::lockForUpdate()
                    ->find($payment->order_id);

                if (!$order) {
                    return;
                }

                /*
                 * COMPLETED
                 */
                if ($status === 'COMPLETED') {

                    $payment->update([
                        'status' => 'paid',

                        'message' =>
                            'Payment completed successfully.',

                        'paid_at' =>
                            now(),

                        'raw_response' =>
                            json_encode(
                                $payload,
                                JSON_UNESCAPED_SLASHES
                            ),
                    ]);

                    $order->update([
                        'payment_status' =>
                            'paid',

                        'order_status' =>
                            'processing',

                        'paid_at' =>
                            now(),
                    ]);

                    /*
                     * Reduce stock only once
                     * after successful payment.
                     */
                    foreach (
                        $order->items as $item
                    ) {

                        $product =
                            $item->product;

                        if (!$product) {
                            continue;
                        }

                        $quantity = min(
                            $item->quantity,
                            $product->stock
                        );

                        if ($quantity > 0) {

                            $product->decrement(
                                'stock',
                                $quantity
                            );
                        }

                        $product->refresh();

                        $product->update([
                            'is_in_stock' =>
                                $product->stock > 0,
                        ]);
                    }

                    /*
                     * Clear cart only after
                     * successful payment.
                     */
                    $cart = Cart::where(
                        'user_id',
                        $order->user_id
                    )->first();

                    if ($cart) {
                        $cart->items()->delete();
                    }

                    return;
                }

                /*
                 * FAILED
                 */
                if ($status === 'FAILED') {

                    $payment->update([
                        'status' => 'failed',

                        'message' =>
                            'PalmPesa payment failed.',

                        'raw_response' =>
                            json_encode(
                                $payload,
                                JSON_UNESCAPED_SLASHES
                            ),
                    ]);

                    $order->update([
                        'payment_status' =>
                            'failed',

                        'order_status' =>
                            'pending',
                    ]);
                }
            }
        );

        return response()->json([
            'success' => true,
            'message' =>
                'Webhook processed successfully.',
        ]);
    }
}
