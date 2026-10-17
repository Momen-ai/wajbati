<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $maxQty = config('shop.max_qty_per_item', 20);

        return [
            'meal_id' => ['required', 'integer', 'exists:meals,id'],
            'quantity' => ['required', 'integer', 'min:1', "max:{$maxQty}"],
        ];
    }
}
