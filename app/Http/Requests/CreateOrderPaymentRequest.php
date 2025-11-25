<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Ajusta según reglas de seguridad
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_method_type' => ['sometimes', 'string', 'max:50'],
            'amount' => ['sometimes', 'numeric', 'min:0'],
            'meta' => ['sometimes', 'array'],
        ];
    }

    public function paymentData(): array
    {
        return [
            'payment_method_type' => $this->input('payment_method_type'),
            'amount' => $this->input('amount'),
            'meta' => $this->input('meta'),
        ];
    }
}
