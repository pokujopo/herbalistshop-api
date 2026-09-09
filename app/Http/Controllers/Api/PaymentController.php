<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Services\Payments\PaymentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function pay(
        Request $request,
        PaymentManager $paymentManager
    ) {
        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'region' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'street_address' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
            'payment_method' => [
                'required',
                'in:mpesa,mixx,cash'
            ],
        ]);

        $cart = Cart::with('items.product')
            ->where(
                'user_id',
                $request->user()->id
            )
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty.',
            ], 422);
        }

        $subtotal = $cart->items->sum(
            fn ($item) =>
                $item->unit_price * $item->quantity
        );

        $deliveryFee = 0;

        $total = $subtotal + $deliveryFee;

        DB::beginTransaction();

        try {

            $address = Address::create([
                'user_id' => $request->user()->id,
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'region' => $request->region,
                'city' => $request->city,
                'street_address' => $request->street_address,
                'notes' => $request->notes,
            ]);

            $order = Order::create([
                'user_id' => $request->user()->id,

                'address_id' => $address->id,

                'order_number' =>
                    'ORD-' . strtoupper(
                        Str::random(8)
                    ),

                'subtotal' => $subtotal,

                'delivery_fee' => $deliveryFee,

                'total' => $total,

                'payment_method' =>
                    $request->payment_method,

                'payment_status' => 'pending',

                'order_status' => 'pending',
            ]);

            foreach ($cart->items as $item) {

                OrderItem::create([
                    'order_id' => $order->id,

                    'product_id' =>
                        $item->product_id,

                    'product_name' =>
                        $item->product?->name,

                    'product_image' =>
                        $item->product?->thumbnail,

                    'quantity' =>
                        $item->quantity,

                    'unit_price' =>
                        $item->unit_price,

                    'subtotal' =>
                        $item->unit_price *
                        $item->quantity,
                ]);
            }

            /*
             * Cash
             */
            if ($request->payment_method === 'cash') {

                $payment = Payment::create([
                    'order_id' => $order->id,
                    'provider' => 'cash',
                    'payment_method' => 'cash',
                    'phone' => $request->phone,
                    'amount' => $total,
                    'currency' => 'TZS',
                    'status' => 'pending',
                ]);

                /*
                 * Cash order can clear cart immediately.
                 */
                $cart->items()->delete();

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' =>
                        'Order created successfully. Pay on delivery.',

                    'order_number' =>
                        $order->order_number,

                    'payment_status' =>
                        'pending',

                    'order_status' =>
                        'pending',
                ], 201);
            }

            /*
             * PalmPesa
             */
            $provider = 'palmpesa';

            $payment = Payment::create([
                'order_id' => $order->id,

                'provider' => $provider,

                'payment_method' =>
                    $request->payment_method,

                'phone' => $request->phone,

                'amount' => $total,

                'currency' => 'TZS',

                'status' => 'pending',
            ]);

            /*
             * User's email comes from authenticated account.
             */
            $gatewayResponse =
                $paymentManager->initiate(
                    $provider,
                    [
                        'payment' => $payment,

                        'order' => $order,

                        'phone' => $request->phone,

                        'amount' => $total,

                        'currency' => 'TZS',
                    ]
                );

            $payment->update([
                'provider_reference' =>
                    $gatewayResponse[
                        'provider_reference'
                    ] ?? null,

                'transaction_reference' =>
                    $gatewayResponse[
                        'transaction_reference'
                    ] ?? $payment->transaction_reference,

                'message' =>
                    $gatewayResponse['message']
                    ?? null,

                'raw_response' =>
                    json_encode(
                        $gatewayResponse,
                        JSON_UNESCAPED_SLASHES
                    ),

                'status' =>
                    $gatewayResponse['status']
                    ?? 'pending',
            ]);

            /*
             * DO NOT delete cart here.
             *
             * Wait for successful payment webhook.
             */

            DB::commit();

            return response()->json([
                'success' => true,

                'message' =>
                    $gatewayResponse['message']
                    ?? 'Payment request sent to your phone.',

                'order_number' =>
                    $order->order_number,

                'payment_status' =>
                    $payment->status,

                'order_status' =>
                    $order->order_status,

                'checkout_url' => null,

                'transaction_reference' =>
                    $payment->transaction_reference,

                'provider_reference' =>
                    $payment->provider_reference,

                'raw' =>
                    $gatewayResponse['raw']
                    ?? null,

            ], 201);

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return response()->json([
                'success' => false,

                'message' =>
                    'Payment initiation failed.',

                'error' =>
                    $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check current order/payment status.
     */
    public function status(
        Request $request,
        $orderNumber
    ) {
        $order = Order::with([
            'latestPayment'
        ])
            ->where(
                'user_id',
                $request->user()->id
            )
            ->where(
                'order_number',
                $orderNumber
            )
            ->firstOrFail();

        return response()->json([
            'success' => true,

            'order_number' =>
                $order->order_number,

            'order_status' =>
                $order->order_status,

            'payment_status' =>
                $order->payment_status,

            'tracking_number' =>
                $order->tracking_number,

            'payment' =>
                $order->latestPayment,
        ]);
    }

    /**
     * Retry failed/pending payment.
     */
    public function retry(
        Request $request,
        $orderNumber,
        PaymentManager $paymentManager
    ) {
        $order = Order::where(
            'user_id',
            $request->user()->id
        )
            ->where(
                'order_number',
                $orderNumber
            )
            ->firstOrFail();

        $payment = $order->payments()
            ->latest()
            ->first();

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' =>
                    'No payment found for this order.',
            ], 404);
        }

        if (
            $payment->payment_method === 'cash'
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'Cash payment cannot be retried.',
            ], 422);
        }

        if ($payment->status === 'paid') {
            return response()->json([
                'success' => false,
                'message' =>
                    'This payment is already completed.',
            ], 422);
        }

        $gatewayResponse =
            $paymentManager->initiate(
                $payment->provider,
                [
                    'payment' => $payment,
                    'order' => $order,
                    'phone' => $payment->phone,
                    'amount' => $payment->amount,
                    'currency' => $payment->currency,
                ]
            );

        $payment->update([
            'provider_reference' =>
                $gatewayResponse[
                    'provider_reference'
                ] ?? $payment->provider_reference,

            'transaction_reference' =>
                $gatewayResponse[
                    'transaction_reference'
                ] ?? $payment->transaction_reference,

            'message' =>
                $gatewayResponse['message']
                ?? $payment->message,

            'raw_response' =>
                json_encode(
                    $gatewayResponse,
                    JSON_UNESCAPED_SLASHES
                ),

            'status' =>
                $gatewayResponse['status']
                ?? 'pending',
        ]);

        return response()->json([
            'success' => true,

            'message' =>
                $gatewayResponse['message']
                ?? 'Payment request sent to your phone.',

            'order_number' =>
                $order->order_number,

            'payment' => $payment,
        ]);
    }
}
