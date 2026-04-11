<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $products = $request->user()
            ->wishlistProducts()
            ->with('category')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'wishlist' => ProductResource::collection($products),
        ]);
    }

    public function toggle(Request $request, Product $product)
    {
        $exists = $request->user()
            ->wishlistProducts()
            ->where('product_id', $product->id)
            ->exists();

        if ($exists) {
            $request->user()->wishlistProducts()->detach($product->id);

            return response()->json([
                'success' => true,
                'wishlisted' => false,
                'message' => 'Removed from wishlist.',
            ]);
        }

        $request->user()->wishlistProducts()->attach($product->id);

        return response()->json([
            'success' => true,
            'wishlisted' => true,
            'message' => 'Added to wishlist.',
        ]);
    }
}