<?php
namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        $price = fake()->numberBetween(10000, 80000);
        $discount = fake()->boolean(70) ? fake()->numberBetween(5000, $price - 1000) : null;

        return [
            'category_id' => Category::factory(),
            'name' => ucfirst($name),
            'slug' => Str::slug($name . '-' . fake()->unique()->numberBetween(1, 9999)),
            'short_description' => fake()->sentence(),
            'description' => fake()->paragraph(4),
            'price' => $price,
            'discount_price' => $discount,
            'thumbnail' => 'https://via.placeholder.com/600x600.png?text=Product',
            'stock' => fake()->numberBetween(0, 50),
            'rating' => fake()->randomFloat(1, 3.5, 5),
            'reviews_count' => fake()->numberBetween(0, 200),
            'is_featured' => fake()->boolean(20),
            'is_best_seller' => fake()->boolean(20),
            'is_new' => fake()->boolean(40),
            'is_active' => true,
            'sku' => strtoupper(fake()->bothify('SKU-####??')),
        ];
    }
}