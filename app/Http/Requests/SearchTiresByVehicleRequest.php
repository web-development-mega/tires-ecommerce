<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchTiresByVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Adjust if you want to restrict this endpoint
        return true;
    }

    public function rules(): array
    {
        return [
            'vehicle_id' => ['required', 'integer', 'exists:vehicles,id'],

            // Optional filters
            'brand_id' => ['sometimes', 'integer', 'exists:brands,id'],
            'min_price' => ['sometimes', 'numeric', 'min:0'],
            'max_price' => ['sometimes', 'numeric', 'min:0'],
            'min_load_index' => ['sometimes', 'integer', 'min:0'],
            'min_speed_rating' => ['sometimes', 'string', 'max:3'],
            'usage' => ['sometimes', 'string', 'max:50'], // TireUsage value

            'is_runflat' => ['sometimes', 'boolean'],
            'is_all_terrain' => ['sometimes', 'boolean'],

            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }

    /**
     * Normalize filters array to be used by the domain service.
     */
    public function filters(): array
    {
        return [
            'brand_id' => $this->integer('brand_id') ?: null,
            'min_price' => $this->input('min_price') !== null ? (float) $this->input('min_price') : null,
            'max_price' => $this->input('max_price') !== null ? (float) $this->input('max_price') : null,
            'min_load_index' => $this->input('min_load_index') !== null ? (int) $this->input('min_load_index') : null,
            'min_speed_rating' => $this->input('min_speed_rating'),
            'usage' => $this->input('usage'),

            'is_runflat' => $this->boolean('is_runflat'),
            'is_all_terrain' => $this->boolean('is_all_terrain'),
        ];
    }
}
