<?php

namespace App\Http\Requests;
use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth::check() && auth()->user()->usertype === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:products,slug'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'thumbnail' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'category_id' => ['required', 'exists:categories,id'],
            'is_featured' => ['nullable', 'boolean'],
            'is_best_seller' => ['nullable', 'boolean'],
            'is_new' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sku' => ['nullable', 'string', 'max:100', 'unique:products,sku'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Jina la bidhaa linahitajika.',
            'slug.required' => 'Slug inahitajika.',
            'slug.unique' => 'Slug hii tayari ipo.',
            'price.required' => 'Bei inahitajika.',
            'price.numeric' => 'Bei lazima iwe namba.',
            'discount_price.numeric' => 'Discount price lazima iwe namba.',
            'stock.required' => 'Stock inahitajika.',
            'stock.integer' => 'Stock lazima iwe integer.',
            'thumbnail.required' => 'Thumbnail image inahitajika.',
            'thumbnail.image' => 'Thumbnail lazima iwe picha.',
            'thumbnail.mimes' => 'Thumbnail inaruhusiwa jpg, jpeg, png, au webp tu.',
            'thumbnail.max' => 'Thumbnail isiwe kubwa kuliko 2MB.',
            'category_id.required' => 'Category inahitajika.',
            'category_id.exists' => 'Category uliyochagua haipo.',
            'sku.unique' => 'SKU hiyo tayari ipo.',
        ];
    }
}