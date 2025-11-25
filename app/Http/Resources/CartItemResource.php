<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\CartItem */
class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $buyable = $this->whenLoaded('buyable');

        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'unit_price' => (float) $this->unit_price,
            'discount' => (float) $this->discount_amount,
            'total' => (float) $this->total,
            'meta' => $this->meta,

            'product' => $buyable ? [
                'id' => $buyable->id,
                'type' => class_basename($this->buyable_type),
                'name' => $buyable->name ?? null,
                'slug' => $buyable->slug ?? null,
            ] : null,
        ];
    }
}
