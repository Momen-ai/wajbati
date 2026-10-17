<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $allowedPaymentMethods = ['cash'];
        if (config('shop.card_enabled', false)) {
            $allowedPaymentMethods[] = 'card';
        }

        return [
            'address_id' => [
                'nullable',
                Rule::exists('addresses', 'id')->where('user_id', $this->user()?->id),
            ],
            'address' => ['required_without:address_id', 'nullable', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^\+?[0-9]{9,15}$/'],
            'payment_method' => ['required', Rule::in($allowedPaymentMethods)],
        ];
    }

    public function messages(): array
    {
        return [
            'payment_method.in' => __('The selected payment method is currently unavailable.'),
            'address_id.exists' => __('The selected address does not belong to your account.'),
        ];
    }
}
