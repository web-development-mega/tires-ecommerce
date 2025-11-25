<?php

namespace App\Imports;

use App\Models\Brand;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BrandsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Brand([
            'name' => $row['nombre'] ?? $row['name'],
            'slug' => $row['slug'] ?? Str::slug($row['nombre'] ?? $row['name']),
            'website_url' => $row['sitio_web'] ?? $row['website_url'] ?? null,
            'country' => $row['pais'] ?? $row['country'] ?? null,
            'is_active' => isset($row['activo']) ? (bool) $row['activo'] : (isset($row['is_active']) ? (bool) $row['is_active'] : true),
        ]);
    }

    public function rules(): array
    {
        return [
            '*.nombre' => ['sometimes', 'string', 'max:255'],
            '*.name' => ['sometimes', 'string', 'max:255'],
            '*.sitio_web' => ['nullable', 'url'],
            '*.website_url' => ['nullable', 'url'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nombre.required' => 'El nombre es obligatorio',
            'name.required' => 'El nombre es obligatorio',
            'sitio_web.url' => 'El sitio web debe ser una URL válida',
            'website_url.url' => 'El sitio web debe ser una URL válida',
        ];
    }
}
