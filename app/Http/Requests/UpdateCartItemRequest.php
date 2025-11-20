<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cart_token' => ['sometimes', 'string', 'max:64'],
            'quantity'   => ['required', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function cartToken(): ?string
    {
        return $this->input('cart_token');
    }

    public function quantity(): int
    {
        return (int) $this->input('quantity');
    }
}
