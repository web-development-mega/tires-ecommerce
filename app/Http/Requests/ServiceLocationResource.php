<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\ServiceLocation */
class ServiceLocationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'slug'         => $this->slug,
            'municipality' => $this->city,
            'department'   => $this->state,
            'country'      => $this->country,

            'contact'      => [
                'phone'    => $this->phone,
                'email'    => $this->email,
                'whatsapp' => $this->whatsapp,
            ],

            'address'      => [
                'line1'       => $this->address_line1,
                'line2'       => $this->address_line2,
                'postal_code' => $this->postal_code,
            ],

            'location'     => [
                'latitude'  => $this->latitude,
                'longitude' => $this->longitude,
            ],

            'opening_hours'=> $this->opening_hours,
            'is_active'    => $this->is_active,

            'services'     => $this->whenLoaded('serviceTypes', function () {
                return $this->serviceTypes->map(fn ($service) => [
                    'id'   => $service->id,
                    'name' => $service->name,
                    'slug' => $service->slug,
                ]);
            }),

            'meta'         => $this->meta,
        ];
    }
}
