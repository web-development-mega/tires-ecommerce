<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Tire */
class TireResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'sku'           => $this->sku,
            'name'          => $this->name,
            'slug'          => $this->slug,
            'pattern'       => $this->pattern,
            'usage'         => $this->usage?->value,
            'brand'         => [
                'id'   => $this->brand->id,
                'name' => $this->brand->name,
                'slug' => $this->brand->slug,
            ],
            'size'          => [
                'id'            => $this->tireSize->id,
                'label'         => $this->tireSize->label,
                'width'         => $this->tireSize->width,
                'aspect_ratio'  => $this->tireSize->aspect_ratio,
                'rim_diameter'  => $this->tireSize->rim_diameter,
            ],

            'load_index'    => $this->load_index,
            'speed_rating'  => $this->speed_rating,

            'utqg'          => [
                'treadwear'  => $this->utqg_treadwear,
                'traction'   => $this->utqg_traction,
                'temperature'=> $this->utqg_temperature,
            ],

            'label'         => [
                'fuel_efficiency' => $this->fuel_efficiency_grade,
                'wet_grip'        => $this->wet_grip_grade,
                'noise_db'        => $this->noise_db,
                'noise_class'     => $this->noise_class,
            ],

            'flags'         => [
                'is_runflat'     => $this->is_runflat,
                'is_all_terrain' => $this->is_all_terrain,
                'is_highway'     => $this->is_highway,
                'is_winter'      => $this->is_winter,
                'is_summer'      => $this->is_summer,
            ],

            'pricing'       => [
                'base_price'     => (float) $this->base_price,
                'sale_price'     => $this->sale_price !== null ? (float) $this->sale_price : null,
                'effective_price'=> (float) $this->effective_price,
                'currency'       => $this->currency,
            ],

            'country_of_origin' => $this->country_of_origin,

            'created_at'    => $this->created_at?->toIso8601String(),
            'updated_at'    => $this->updated_at?->toIso8601String(),
        ];
    }
}
