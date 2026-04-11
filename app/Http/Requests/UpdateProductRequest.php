<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->usertype === 'admin';
    }

    public function rules(): array
    {
        $product = $this->route('product');

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'slug' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('products', 'slug')->ignore($product?->id),
            ],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['sometimes', 'integer', 'min:0'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'category_id' => ['sometimes', 'exists:categories,id'],
            'is_featured' => ['nullable', 'boolean'],
            'is_best_seller' => ['nullable', 'boolean'],
            'is_new' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sku' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('products', 'sku')->ignore($product?->id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'slug.unique' => 'Slug hii tayari ipo kwa bidhaa nyingine.',
            'price.numeric' => 'Bei lazima iwe namba.',
            'discount_price.numeric' => 'Discount price lazima iwe namba.',
            'stock.integer' => 'Stock lazima iwe integer.',
            'thumbnail.image' => 'Thumbnail lazima iwe picha.',
            'thumbnail.mimes' => 'Thumbnail inaruhusiwa jpg, jpeg, png, au webp tu.',
            'thumbnail.max' => 'Thumbnail isiwe kubwa kuliko 2MB.',
            'category_id.exists' => 'Category uliyochagua haipo.',
            'sku.unique' => 'SKU hiyo tayari ipo.',
        ];
    }
}