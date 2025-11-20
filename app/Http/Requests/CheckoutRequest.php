<?php

namespace App\Http\Requests;

use App\Enums\OrderDeliveryType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Allow guests and authenticated users
        return true;
    }

    public function rules(): array
    {
        $deliveryTypes = array_map(
            fn (OrderDeliveryType $case) => $case->value,
            OrderDeliveryType::cases()
        );

        return [
            // Cart context
            'cart_token' => ['sometimes', 'string', 'max:64'],

            // Customer info
            'customer_first_name' => ['required', 'string', 'max:100'],
            'customer_last_name'  => ['required', 'string', 'max:100'],
            'customer_email'      => ['required', 'email', 'max:150'],
            'customer_phone'      => ['required', 'string', 'max:50'],

            'document_type'       => ['nullable', 'string', 'max:20'],
            'document_number'     => ['nullable', 'string', 'max:50'],

            // Delivery / fulfillment
            'delivery_type'       => ['required', 'string', Rule::in($deliveryTypes)],

            // Home delivery address (required if delivery_type = home_delivery)
            'shipping_address_line1' => [
                'required_if:delivery_type,' . OrderDeliveryType::HOME_DELIVERY->value,
                'string',
                'max:255',
            ],
            'shipping_address_line2' => ['nullable', 'string', 'max:255'],
            'shipping_city'          => [
                'required_if:delivery_type,' . OrderDeliveryType::HOME_DELIVERY->value,
                'string',
                'max:100',
            ],
            'shipping_state'         => ['nullable', 'string', 'max:100'],
            'shipping_postal_code'   => ['nullable', 'string', 'max:20'],
            'shipping_country'       => ['nullable', 'string', 'size:2'],

            // Service location (required if delivery_type = service_location)
            'service_location_id' => [
                'required_if:delivery_type,' . OrderDeliveryType::SERVICE_LOCATION->value,
                'integer',
            ],

            // Totals (optional, backend puede recalcular en el futuro)
            'shipping_total'  => ['sometimes', 'numeric', 'min:0'],
            'tax_total'       => ['sometimes', 'numeric', 'min:0'],
            'grand_total'     => ['sometimes', 'numeric', 'min:0'],

            // Extra metadata (notes, extra info, etc.)
            'meta'            => ['sometimes', 'array'],
        ];
    }

    public function cartToken(): ?string
    {
        return $this->input('cart_token');
    }

    /**
     * Extract data meant for OrderService::createFromCart().
     */
    public function checkoutData(): array
    {
        return [
            'customer_first_name'   => $this->input('customer_first_name'),
            'customer_last_name'    => $this->input('customer_last_name'),
            'customer_email'        => $this->input('customer_email'),
            'customer_phone'        => $this->input('customer_phone'),
            'document_type'         => $this->input('document_type'),
            'document_number'       => $this->input('document_number'),

            'delivery_type'         => $this->input('delivery_type'),

            'shipping_address_line1'=> $this->input('shipping_address_line1'),
            'shipping_address_line2'=> $this->input('shipping_address_line2'),
            'shipping_city'         => $this->input('shipping_city'),
            'shipping_state'        => $this->input('shipping_state'),
            'shipping_postal_code'  => $this->input('shipping_postal_code'),
            'shipping_country'      => $this->input('shipping_country', 'CO'),

            'service_location_id'   => $this->input('service_location_id'),

            'shipping_total'        => $this->input('shipping_total'),
            'tax_total'             => $this->input('tax_total'),
            'grand_total'           => $this->input('grand_total'),

            'meta'                  => $this->input('meta'),
        ];
    }
}
