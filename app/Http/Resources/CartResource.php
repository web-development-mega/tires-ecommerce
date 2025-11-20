<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Cart */
class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'token'       => $this->token,
            'status'      => $this->status->value ?? $this->status,
            'currency'    => $this->currency,
            'items_count' => $this->items_count,

            'totals'      => [
                'subtotal'       => (float) $this->subtotal,
                'discount_total' => (float) $this->discount_total,
                'grand_total'    => (float) $this->grand_total,
            ],

            'items'       => CartItemResource::collection(
                $this->whenLoaded('items')
            ),

            'meta'        => $this->meta,
        ];
    }
}
