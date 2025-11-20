<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\OrderItem */
class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'quantity'     => $this->quantity,
            'unit_price'   => (float) $this->unit_price,
            'discount'     => (float) $this->discount_amount,
            'total'        => (float) $this->total,

            'sku'          => $this->sku,
            'name'         => $this->name,
            'slug'         => $this->slug,
            'brand_name'   => $this->brand_name,
            'size_label'   => $this->size_label,

            'meta'         => $this->meta,

            'product'      => $this->whenLoaded('buyable', function () {
                return [
                    'id'   => $this->buyable?->id,
                    'type' => class_basename($this->buyable_type),
                ];
            }),
        ];
    }
}
