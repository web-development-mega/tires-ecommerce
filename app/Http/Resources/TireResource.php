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

            // Brand: protegido con whenLoaded para evitar errores si no se eager-loadea
            'brand'         => $this->whenLoaded('brand', function () {
                return [
                    'id'   => $this->brand->id,
                    'name' => $this->brand->name,
                    'slug' => $this->brand->slug,
                ];
            }),

            // Size: idem, y añadimos un display calculado si es posible
            'size'          => $this->whenLoaded('tireSize', function () {
                $size = $this->tireSize;

                $display = null;
                if ($size->width && $size->aspect_ratio && $size->rim_diameter) {
                    $display = sprintf(
                        '%d/%dR%d',
                        $size->width,
                        $size->aspect_ratio,
                        $size->rim_diameter
                    );
                }

                return [
                    'id'            => $size->id,
                    'label'         => $size->label,
                    'width'         => $size->width,
                    'aspect_ratio'  => $size->aspect_ratio,
                    'rim_diameter'  => $size->rim_diameter,
                    'display'       => $display,
                ];
            }),

            'load_index'    => $this->load_index,
            'speed_rating'  => $this->speed_rating,

            'utqg'          => [
                'treadwear'   => $this->utqg_treadwear,
                'traction'    => $this->utqg_traction,
                'temperature' => $this->utqg_temperature,
            ],

            'label'         => [
                'fuel_efficiency' => $this->fuel_efficiency_grade,
                'wet_grip'        => $this->wet_grip_grade,
                'noise_db'        => $this->noise_db,
                'noise_class'     => $this->noise_class,
            ],

            'flags'         => [
                'is_runflat'     => (bool) $this->is_runflat,
                'is_all_terrain' => (bool) $this->is_all_terrain,
                'is_highway'     => (bool) $this->is_highway,
                'is_winter'      => (bool) $this->is_winter,
                'is_summer'      => (bool) $this->is_summer,
            ],

            'pricing'       => [
                'base_price'      => $this->base_price !== null ? (float) $this->base_price : null,
                'sale_price'      => $this->sale_price !== null ? (float) $this->sale_price : null,
                'effective_price' => $this->effective_price !== null ? (float) $this->effective_price : null,
                'currency'        => $this->currency,
            ],

            'country_of_origin' => $this->country_of_origin,

            'created_at'    => $this->created_at?->toIso8601String(),
            'updated_at'    => $this->updated_at?->toIso8601String(),
        ];
    }
}
