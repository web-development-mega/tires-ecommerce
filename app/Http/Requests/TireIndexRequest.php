<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TireIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Public catalog
        return true;
    }

    public function rules(): array
    {
        return [
            'brand_id'   => ['sometimes', 'integer', 'exists:brands,id'],
            'brand_slug' => ['sometimes', 'string', 'max:150'],

            // Dimensiones (medida) ANCHO / PERFIL / RIN
            'width'      => ['sometimes', 'integer', 'min:100', 'max:400'],
            'profile'    => ['sometimes', 'integer', 'min:20', 'max:90'],
            'rim'        => ['sometimes', 'integer', 'min:10', 'max:26'],

            // Atributos booleanos
            'runflat'     => ['sometimes', 'boolean'],
            'all_terrain' => ['sometimes', 'boolean'],

            // Paginación
            'per_page'    => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function filters(): array
    {
        return [
            'brand_id'    => $this->input('brand_id'),
            'brand_slug'  => $this->input('brand_slug'),
            'width'       => $this->input('width'),
            'profile'     => $this->input('profile'),
            'rim'         => $this->input('rim'),
            'runflat'     => $this->has('runflat') ? $this->boolean('runflat') : null,
            'all_terrain' => $this->has('all_terrain') ? $this->boolean('all_terrain') : null,
        ];
    }

    public function perPage(): int
    {
        return (int) $this->input('per_page', 20);
    }
}
