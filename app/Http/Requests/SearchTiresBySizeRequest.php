<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchTiresBySizeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'width'         => ['required', 'integer', 'min:100', 'max:500'],
            'aspect_ratio'  => ['required', 'integer', 'min:20', 'max:90'],
            'rim_diameter'  => ['required', 'numeric', 'min:10', 'max:30'],

            // Optional filters (same as by vehicle)
            'brand_id'          => ['sometimes', 'integer', 'exists:brands,id'],
            'min_price'         => ['sometimes', 'numeric', 'min:0'],
            'max_price'         => ['sometimes', 'numeric', 'min:0'],
            'min_load_index'    => ['sometimes', 'integer', 'min:0'],
            'min_speed_rating'  => ['sometimes', 'string', 'max:3'],
            'usage'             => ['sometimes', 'string', 'max:50'],

            'is_runflat'        => ['sometimes', 'boolean'],
            'is_all_terrain'    => ['sometimes', 'boolean'],

            'per_page'          => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function filters(): array
    {
        return [
            'brand_id'        => $this->integer('brand_id') ?: null,
            'min_price'       => $this->input('min_price') !== null ? (float) $this->input('min_price') : null,
            'max_price'       => $this->input('max_price') !== null ? (float) $this->input('max_price') : null,
            'min_load_index'  => $this->input('min_load_index') !== null ? (int) $this->input('min_load_index') : null,
            'min_speed_rating'=> $this->input('min_speed_rating'),
            'usage'           => $this->input('usage'),

            'is_runflat'      => $this->boolean('is_runflat'),
            'is_all_terrain'  => $this->boolean('is_all_terrain'),
        ];
    }

    public function width(): int
    {
        return (int) $this->input('width');
    }

    public function aspectRatio(): int
    {
        return (int) $this->input('aspect_ratio');
    }

    public function rimDiameter(): float
    {
        return (float) $this->input('rim_diameter');
    }
}
