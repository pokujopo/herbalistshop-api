<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = Cart::firstOrCreate([
            'user_id' => $request->user()->id,
        ]);

        $cart->load('items.product.category');

        $items = $cart->items->map(function ($item) {
            return [
                'id' => $item->id,
                'quantity' => $item->quantity,
                'unit_price' => (float) $item->unit_price,
                'subtotal' => (float) ($item->unit_price * $item->quantity),
                'product' => [
                    'id' => $item->product?->id,
                    'name' => $item->product?->name,
                    'slug' => $item->product?->slug,
                    'thumbnail' => $item->product?->thumbnail,
                    'stock' => $item->product?->stock,
                    'is_in_stock' => $item->product?->is_in_stock,
                ],
            ];
        });

        $subtotal = $items->sum('subtotal');

        return response()->json([
            'success' => true,
            'cart' => [
                'id' => $cart->id,
                'items' => $items,
                'items_count' => $items->count(),
                'subtotal' => $subtotal,
                'delivery_fee' => 0,
                'total' => $subtotal,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($request->product_id);

        if (!$product->is_in_stock || $product->stock < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Product is out of stock.',
            ], 422);
        }

        $cart = Cart::firstOrCreate([
            'user_id' => $request->user()->id,
        ]);

        $quantity = $request->quantity ?? 1;
        $price = $product->discount_price ?: $product->price;

        $item = CartItem::firstOrNew([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ]);

        $item->quantity = $item->exists ? $item->quantity + $quantity : $quantity;
        $item->unit_price = $price;
        $item->save();

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully.',
        ]);
    }

    public function update(Request $request, CartItem $cartItem)
    {
        abort_if($cartItem->cart->user_id !== $request->user()->id, 403);

        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully.',
        ]);
    }

    public function destroy(Request $request, CartItem $cartItem)
    {
        abort_if($cartItem->cart->user_id !== $request->user()->id, 403);

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart.',
        ]);
    }
}