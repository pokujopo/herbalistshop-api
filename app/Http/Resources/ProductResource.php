<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'price' => (float) $this->price,
            'discount_price' => $this->discount_price ? (float) $this->discount_price : null,
            'discount_percent' => $this->discount_percent,
            'currency' => 'TZS',
            'thumbnail' => $this->thumbnail, // ? asset('storage/' . $this->thumbnail) : null,
            'stock' => $this->stock,
            'is_in_stock' => (bool) $this->is_in_stock,
            'rating' => (float) $this->rating,
            'reviews_count' => $this->reviews_count,
            'is_featured' => (bool) $this->is_featured,
            'is_best_seller' => (bool) $this->is_best_seller,
            'is_new' => (bool) $this->is_new,
            'is_active' => (bool) $this->is_active,
            'sku' => $this->sku,

            'category' => $this->whenLoaded('category', function () {
                return [
                    'id' => $this->category?->id,
                    'name' => $this->category?->name,
                    'slug' => $this->category?->slug,
                ];
            }),

            'images' => $this->whenLoaded('images', function () {
                return $this->images->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'url' => asset('storage/' . $image->image),
                        'is_primary' => (bool) $image->is_primary,
                        'sort_order' => $image->sort_order,
                    ];
                });
            }),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
/*
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'price' => (float) $this->price,
            'discount_price' => $this->discount_price ? (float) $this->discount_price : null,
            'discount_percent' => $this->discount_percent,
            'currency' => 'TZS',
            'thumbnail' => $this->thumbnail,
            'stock' => $this->stock,
            'is_in_stock' => (bool) $this->is_in_stock,
            'rating' => (float) $this->rating,
            'reviews_count' => $this->reviews_count,
            'is_featured' => (bool) $this->is_featured,
            'is_best_seller' => (bool) $this->is_best_seller,
            'is_new' => (bool) $this->is_new,

            'category' => $this->whenLoaded('category', function () {
                return [
                    'id' => $this->category?->id,
                    'name' => $this->category?->name,
                    'slug' => $this->category?->slug,
                ];
            }),

            'images' => $this->whenLoaded('images', function () {
                return $this->images->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'url' => $image->image,
                        'is_primary' => (bool) $image->is_primary,
                        'sort_order' => $image->sort_order,
                    ];
                });
            }),

            'reviews' => $this->whenLoaded('reviews', function () {
                return $this->reviews->map(function ($review) {
                    return [
                        'id' => $review->id,
                        'name' => $review->user?->name ?? 'Anonymous',
                        'rating' => $review->rating,
                        'comment' => $review->comment,
                        'created_at' => $review->created_at?->format('Y-m-d'),
                    ];
                });
            }),

            'created_at' => $this->created_at,
        ];
    }
}
    */