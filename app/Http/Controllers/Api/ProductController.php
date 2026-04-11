<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')
            ->where('is_active', true);

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if ($request->sort === 'low') {
            $query->orderByRaw('COALESCE(discount_price, price) ASC');
        } elseif ($request->sort === 'high') {
            $query->orderByRaw('COALESCE(discount_price, price) DESC');
        } elseif ($request->sort === 'popular') {
            $query->orderByDesc('reviews_count')->orderByDesc('rating');
        } else {
            $query->latest();
        }

        return ProductResource::collection($query->paginate(12));
    }

    public function show($slug)
    {
        $product = Product::with(['category', 'images', 'reviews.user'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedProducts = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->latest()
            ->take(4)
            ->get();

        return response()->json([
            'success' => true,
            'product' => new ProductResource($product),
            'related_products' => ProductResource::collection($relatedProducts),
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        $thumbnailPath = $request->file('thumbnail')->store('products', 'public');

        $product = Product::create([
            ...$data,
            'thumbnail' => $thumbnailPath,
            'is_featured' => $request->boolean('is_featured'),
            'is_best_seller' => $request->boolean('is_best_seller'),
            'is_new' => $request->has('is_new') ? $request->boolean('is_new') : true,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
        ]);

        ProductImage::create([
            'product_id' => $product->id,
            'image' => $thumbnailPath,
            'is_primary' => true,
            'sort_order' => 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully.',
            'product' => new ProductResource($product->load(['category', 'images'])),
        ], 201);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();

        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
                Storage::disk('public')->delete($product->thumbnail);
            }

            $newThumbnailPath = $request->file('thumbnail')->store('products', 'public');
            $data['thumbnail'] = $newThumbnailPath;

            $primaryImage = $product->images()->where('is_primary', true)->first();

            if ($primaryImage) {
                if ($primaryImage->image && Storage::disk('public')->exists($primaryImage->image)) {
                    Storage::disk('public')->delete($primaryImage->image);
                }

                $primaryImage->update([
                    'image' => $newThumbnailPath,
                ]);
            } else {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $newThumbnailPath,
                    'is_primary' => true,
                    'sort_order' => 1,
                ]);
            }
        }

        if ($request->has('is_featured')) {
            $data['is_featured'] = $request->boolean('is_featured');
        }

        if ($request->has('is_best_seller')) {
            $data['is_best_seller'] = $request->boolean('is_best_seller');
        }

        if ($request->has('is_new')) {
            $data['is_new'] = $request->boolean('is_new');
        }

        if ($request->has('is_active')) {
            $data['is_active'] = $request->boolean('is_active');
        }

        $product->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully.',
            'product' => new ProductResource($product->fresh()->load(['category', 'images'])),
        ]);
    }

    public function destroy(Product $product)
    {
        if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        foreach ($product->images as $image) {
            if ($image->image && Storage::disk('public')->exists($image->image)) {
                Storage::disk('public')->delete($image->image);
            }
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully.',
        ]);
    }

    public function uploadImages(Request $request, Product $product)
    {
        $request->validate([
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $lastSort = (int) $product->images()->max('sort_order');
        $uploadedImages = [];

        foreach ($request->file('images') as $index => $file) {
            $path = $file->store('products/gallery', 'public');

            $image = ProductImage::create([
                'product_id' => $product->id,
                'image' => $path,
                'is_primary' => false,
                'sort_order' => $lastSort + $index + 1,
            ]);

            $uploadedImages[] = [
                'id' => $image->id,
                'url' => asset('storage/' . $image->image),
                'is_primary' => false,
                'sort_order' => $image->sort_order,
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Product gallery images uploaded successfully.',
            'images' => $uploadedImages,
        ], 201);
    }

    public function deleteImage(ProductImage $image)
    {
        if ($image->is_primary) {
            return response()->json([
                'success' => false,
                'message' => 'Primary image cannot be deleted directly.',
            ], 422);
        }

        if ($image->image && Storage::disk('public')->exists($image->image)) {
            Storage::disk('public')->delete($image->image);
        }

        $image->delete();

        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully.',
        ]);
    }
}
/*
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')
            ->where('is_active', true);

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if ($request->sort === 'low') {
            $query->orderByRaw('COALESCE(discount_price, price) ASC');
        } elseif ($request->sort === 'high') {
            $query->orderByRaw('COALESCE(discount_price, price) DESC');
        } elseif ($request->sort === 'popular') {
            $query->orderByDesc('reviews_count')
                  ->orderByDesc('rating');
        } else {
            $query->latest();
        }

        $products = $query->paginate(12);

        return ProductResource::collection($products);
    }

    public function show($slug)
    {
        $product = Product::with([
                'category',
                'images',
                'reviews.user',
            ])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedProducts = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->latest()
            ->take(4)
            ->get();

        return response()->json([
            'success' => true,
            'product' => new ProductResource($product),
            'related_products' => ProductResource::collection($relatedProducts),
        ]);
    }

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'required|string|unique:products,slug',
        'short_description' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'discount_price' => 'nullable|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'thumbnail' => 'required|image|max:2048', // optional file upload validation
        'category_id' => 'required|exists:categories,id',
        'is_featured' => 'boolean',
        'is_best_seller' => 'boolean',
        'is_new' => 'boolean',
    ]);

    // Upload image
    $thumbnailPath = $request->file('thumbnail')->store('products', 'public');

    $product = Product::create([
        'name' => $request->name,
        'slug' => $request->slug,
        'short_description' => $request->short_description,
        'description' => $request->description,
        'price' => $request->price,
        'discount_price' => $request->discount_price,
        'thumbnail' => $thumbnailPath,
        'stock' => $request->stock,
        'category_id' => $request->category_id,
        'is_featured' => $request->is_featured ?? false,
        'is_best_seller' => $request->is_best_seller ?? false,
        'is_new' => $request->is_new ?? true,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Product created successfully',
        'product' => $product,
    ], 201);
}
}

*/