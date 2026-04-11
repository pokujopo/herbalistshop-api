<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['items.product', 'address', 'user'])->latest();

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('address', function ($sub) use ($search) {
                      $sub->where('full_name', 'like', "%{$search}%")
                          ->orWhere('phone', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function ($sub) use ($search) {
                      $sub->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        return response()->json($query->paginate(20));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'address', 'user']);

        return response()->json([
            'success' => true,
            'order' => $order,
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'order_status' => ['nullable', 'in:pending,processing,shipped,delivered,cancelled'],
            'payment_status' => ['nullable', 'in:pending,paid,failed'],
            'tracking_number' => ['nullable', 'string', 'max:255'],
        ]);

        if (array_key_exists('order_status', $data)) {
            $order->order_status = $data['order_status'];

            if ($data['order_status'] === 'shipped' && !$order->shipped_at) {
                $order->shipped_at = now();
            }

            if ($data['order_status'] === 'delivered' && !$order->delivered_at) {
                $order->delivered_at = now();
            }
        }

        if (array_key_exists('payment_status', $data)) {
            $order->payment_status = $data['payment_status'];

            if ($data['payment_status'] === 'paid' && !$order->paid_at) {
                $order->paid_at = now();
            }
        }

        if (array_key_exists('tracking_number', $data)) {
            $order->tracking_number = $data['tracking_number'];
        }

        $order->save();
        $order->load(['items.product', 'address', 'user']);

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully.',
            'order' => $order,
        ]);
    }
}