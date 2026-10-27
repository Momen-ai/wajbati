<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user?->id),
            ],
            'phone' => [
                'nullable',
                'string',
                'regex:/^\+?[0-9]{9,15}$/',
                Rule::unique(User::class)->ignore($user?->id),
            ],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];

        if ($user && $user->role === 'chef') {
            $rules['kitchen_name'] = ['nullable', 'string', 'max:255'];
            $rules['bio'] = ['nullable', 'string', 'max:1000'];
            $rules['address'] = ['nullable', 'string', 'max:255'];
        }

        return $rules;
    }
}
