<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Order */
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'status' => $this->status?->value ?? $this->status,
            'currency' => $this->currency,
            'items_count' => $this->items_count,

            'totals' => [
                'subtotal' => (float) $this->subtotal,
                'discount_total' => (float) $this->discount_total,
                'tax_total' => (float) $this->tax_total,
                'shipping_total' => (float) $this->shipping_total,
                'grand_total' => (float) $this->grand_total,
            ],

            'customer' => [
                'first_name' => $this->customer_first_name,
                'last_name' => $this->customer_last_name,
                'email' => $this->customer_email,
                'phone' => $this->customer_phone,
                'document' => [
                    'type' => $this->document_type,
                    'number' => $this->document_number,
                ],
            ],

            'delivery' => [
                'type' => $this->delivery_type?->value ?? $this->delivery_type,

                'shipping_address' => [
                    'line1' => $this->shipping_address_line1,
                    'line2' => $this->shipping_address_line2,
                    'city' => $this->shipping_city,
                    'state' => $this->shipping_state,
                    'postal_code' => $this->shipping_postal_code,
                    'country' => $this->shipping_country,
                ],

                'service_location_id' => $this->service_location_id,
            ],

            'service_location' => $this->whenLoaded('serviceLocation', function () {
                return [
                    'id' => $this->serviceLocation->id,
                    'name' => $this->serviceLocation->name,
                    'municipality' => $this->serviceLocation->city,
                    'department' => $this->serviceLocation->state,
                    'address' => [
                        'line1' => $this->serviceLocation->address_line1,
                        'line2' => $this->serviceLocation->address_line2,
                        'postal_code' => $this->serviceLocation->postal_code,
                    ],
                    'contact' => [
                        'phone' => $this->serviceLocation->phone,
                        'whatsapp' => $this->serviceLocation->whatsapp,
                    ],
                ];
            }),

            'meta' => $this->meta,

            'items' => OrderItemResource::collection(
                $this->whenLoaded('items')
            ),

            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
