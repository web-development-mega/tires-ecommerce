<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrackOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Público: tracking de pedido solo requiere datos del cliente
        return true;
    }

    public function rules(): array
    {
        return [
            'order_number'    => ['required', 'string', 'max:50'],
            'customer_email'  => ['required_without:document_number', 'email', 'max:150'],
            'document_number' => ['required_without:customer_email', 'string', 'max:50'],
        ];
    }

    public function filters(): array
    {
        return [
            'order_number'    => $this->input('order_number'),
            'customer_email'  => $this->input('customer_email'),
            'document_number' => $this->input('document_number'),
        ];
    }
}
