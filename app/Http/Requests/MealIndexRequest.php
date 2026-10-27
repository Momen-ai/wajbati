<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MealIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => 'nullable|string|max:100',
            'category' => 'nullable|integer|exists:categories,id',
            'chef' => 'nullable|integer|exists:users,id',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0|gte:min_price',
            'sort' => 'nullable|string|in:price_low,price_high,top_rated,latest',
        ];
    }
}
