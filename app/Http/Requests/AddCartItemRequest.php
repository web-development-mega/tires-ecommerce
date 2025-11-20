<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // adjust if you need auth
    }

    public function rules(): array
    {
        return [
            'cart_token' => ['sometimes', 'string', 'max:64'],

            'tire_id'    => ['required', 'integer', 'exists:tires,id'],
            'quantity'   => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function cartToken(): ?string
    {
        return $this->input('cart_token');
    }

    public function tireId(): int
    {
        return (int) $this->input('tire_id');
    }

    public function quantity(): int
    {
        return (int) $this->input('quantity', 1);
    }
}
