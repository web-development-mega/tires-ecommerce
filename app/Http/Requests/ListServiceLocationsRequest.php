<?php

namespace App\Http\Requests;

use App\Enums\MetroMunicipality;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListServiceLocationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // public endpoint
    }

    public function rules(): array
    {
        $municipalities = array_map(
            fn (MetroMunicipality $m) => $m->value,
            MetroMunicipality::cases()
        );

        return [
            'municipality' => ['sometimes', 'string', Rule::in($municipalities)],
            'service_slug' => ['sometimes', 'string', 'max:150'],
            'only_active' => ['sometimes', 'boolean'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function filters(): array
    {
        return [
            'municipality' => $this->input('municipality'),
            'service_slug' => $this->input('service_slug'),
            'only_active' => $this->boolean('only_active', true),
        ];
    }

    public function perPage(): int
    {
        return (int) $this->input('per_page', 20);
    }
}
