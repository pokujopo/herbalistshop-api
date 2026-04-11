<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'short_description',
        'description',
        'price',
        'discount_price',
        'discount_percent',
        'thumbnail',
        'stock',
        'is_in_stock',
        'rating',
        'reviews_count',
        'is_featured',
        'is_best_seller',
        'is_new',
        'is_active',
        'sku',
    ];
    public function getThumbnailAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($product) {
            if ($product->discount_price && $product->price > 0) {
                $product->discount_percent = (int) round(
                    (($product->price - $product->discount_price) / $product->price) * 100
                );
            } else {
                $product->discount_percent = null;
            }

            $product->is_in_stock = $product->stock > 0;
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function wishlistedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlists');
    }
}