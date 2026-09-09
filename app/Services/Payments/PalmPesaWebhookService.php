<?php

namespace App\Services\Payments;

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

        Log::info('PalmPesa webhook received', [
            'payload' => $payload,
        ]);

        /*
         * PalmPesa responses can be nested.
         * We try the common locations.
         */
        $data = $payload['data'][0]
            ?? $payload['data']
            ?? $payload['response']
            ?? $payload;

        if (!is_array($data)) {
            $data = $payload;
        }

        $transactionId =
            $data['transaction_id']
            ?? $data['transactionId']
            ?? $data['transid']
            ?? $payload['transaction_id']
            ?? $payload['transactionId']
            ?? null;

        $providerReference =
            $data['order_id']
            ?? $data['orderId']
            ?? $payload['order_id']
            ?? $payload['orderId']
            ?? null;

        $status =
            strtoupper(
                $data['payment_status']
                ?? $data['status']
                ?? $data['result']
                ?? $payload['payment_status']
                ?? $payload['status']
                ?? ''
            );

        $resultCode =
            (string) (
                $data['resultcode']
                ?? $data['result_code']
                ?? $payload['resultcode']
                ?? ''
            );

        /*
         * Find payment using our transaction ID first.
         */
        $payment = null;

        if ($transactionId) {
            $payment = Payment::where(
                'transaction_reference',
                $transactionId
            )->first();
        }

        /*
         * Fallback to PalmPesa reference.
         */
        if (!$payment && $providerReference) {
            $payment = Payment::where(
                'provider_reference',
                $providerReference
            )->first();
        }

        if (!$payment) {
            Log::warning('PalmPesa webhook payment not found', [
                'transaction_id' => $transactionId,
                'provider_reference' => $providerReference,
                'payload' => $payload,
            ]);

            /*
             * Return 200 so PalmPesa doesn't repeatedly send
             * an unknown webhook forever.
             */
            return response()->json([
                'success' => false,
                'message' => 'Payment not found.',
            ], 200);
        }

        /*
         * IMPORTANT:
         * Do not process the same successful webhook twice.
         */
        if ($payment->status === 'paid') {
            return response()->json([
                'success' => true,
                'message' => 'Payment already processed.',
            ]);
        }

        $paymentStatus = $this->resolveStatus(
            $status,
            $resultCode
        );

        DB::transaction(function () use (
            $payment,
            $providerReference,
            $payload,
            $paymentStatus
        ) {
            $payment->update([
                'provider_reference' =>
                    $providerReference
                    ?? $payment->provider_reference,

                'status' => $paymentStatus,

                'message' =>
                    $payload['message']
                    ?? $payload['response']['message']
                    ?? $payment->message,

                'raw_response' => json_encode(
                    $payload,
                    JSON_UNESCAPED_SLASHES
                ),
            ]);

            $order = Order::lockForUpdate()
                ->find($payment->order_id);

            if (!$order) {
                return;
            }

            if ($paymentStatus === 'paid') {

                $order->update([
                    'payment_status' => 'paid',
                    'order_status' => 'processing',
                    'paid_at' => now(),
                ]);

                /*
                 * Reduce stock ONLY after successful payment.
                 */
                foreach ($order->items as $item) {
                    $product = $item->product;

                    if (!$product) {
                        continue;
                    }

                    /*
                     * Prevent stock from becoming negative.
                     */
                    $quantity = min(
                        $item->quantity,
                        $product->stock
                    );

                    $product->decrement(
                        'stock',
                        $quantity
                    );

                    $product->refresh();

                    $product->is_in_stock =
                        $product->stock > 0;

                    $product->save();
                }

                /*
                 * Delete user's cart only after successful payment.
                 */
                if ($order->user_id) {
                    $cart = \App\Models\Cart::where(
                        'user_id',
                        $order->user_id
                    )->first();

                    $cart?->items()->delete();
                }

            } elseif ($paymentStatus === 'failed') {

                $order->update([
                    'payment_status' => 'failed',
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Webhook processed successfully.',
        ]);
    }

    private function resolveStatus(
        string $status,
        string $resultCode
    ): string {
        $paidStatuses = [
            'PAID',
            'SUCCESSFUL',
            'COMPLETED',
            'SUCCESS',
        ];

        $failedStatuses = [
            'FAILED',
            'FAIL',
            'CANCELLED',
            'CANCELED',
            'DECLINED',
            'EXPIRED',
        ];

        if (
            in_array($status, $paidStatuses, true)
            && $resultCode !== '999'
        ) {
            return 'paid';
        }

        if (
            in_array($status, $failedStatuses, true)
        ) {
            return 'failed';
        }

        return 'pending';
    }
}
