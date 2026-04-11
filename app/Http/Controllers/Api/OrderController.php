<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Helpers\AdminNotificationHelper;


class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['items.product', 'address'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'region' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'street_address' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
            'payment_method' => ['required', 'in:mpesa,mixx,cash'],
        ]);

        $cart = Cart::with('items.product')
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty.',
            ], 422);
        }

        $subtotal = $cart->items->sum(fn($item) => $item->unit_price * $item->quantity);
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
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'order_status' => 'pending',
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product?->name,
                    'product_image' => $item->product?->thumbnail,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'subtotal' => $item->unit_price * $item->quantity,
                ]);

                if ($item->product) {
                    $item->product->decrement('stock', $item->quantity);
                    $item->product->refresh();
                    $item->product->is_in_stock = $item->product->stock > 0;
                    $item->product->save();
                }
            }

            $cart->items()->delete();

            DB::commit();

            AdminNotificationHelper::create(
                'New order received',
                'Order ' . $order->order_number . ' has been placed.',
                'new_order',
                '/admin/orders',
                ['order_id' => $order->id, 'order_number' => $order->order_number]
            );

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully.',
                'order_number' => $order->order_number,
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Order creation failed.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Request $request, $orderNumber)
    {
        $order = Order::with(['items.product', 'address'])
            ->where('user_id', $request->user()->id)
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'order' => $order,
        ]);
    }

    public function track(Request $request, $orderNumber)
    {
        $order = Order::where('user_id', $request->user()->id)
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'tracking' => [
                'order_number' => $order->order_number,
                'status' => $order->order_status,
                'payment_status' => $order->payment_status,
                'tracking_number' => $order->tracking_number,
                'paid_at' => $order->paid_at,
                'shipped_at' => $order->shipped_at,
                'delivered_at' => $order->delivered_at,
            ],
        ]);
    }
}