<?php

namespace App\Imports;

use App\Models\TireSize;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class TireSizesImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $width = $row['ancho'] ?? $row['width'];
        $aspectRatio = $row['perfil'] ?? $row['aspect_ratio'];
        $rimDiameter = $row['diametro'] ?? $row['rim_diameter'];

        return new TireSize([
            'width' => $width,
            'aspect_ratio' => $aspectRatio,
            'rim_diameter' => $rimDiameter,
            'slug' => $row['slug'] ?? "{$width}/{$aspectRatio}R{$rimDiameter}",
            'is_active' => isset($row['activo']) ? (bool) $row['activo'] : (isset($row['is_active']) ? (bool) $row['is_active'] : true),
        ]);
    }

    public function rules(): array
    {
        return [
            '*.ancho' => ['sometimes', 'numeric', 'min:100', 'max:400'],
            '*.width' => ['sometimes', 'numeric', 'min:100', 'max:400'],
            '*.perfil' => ['sometimes', 'numeric', 'min:25', 'max:85'],
            '*.aspect_ratio' => ['sometimes', 'numeric', 'min:25', 'max:85'],
            '*.diametro' => ['sometimes', 'numeric', 'min:10', 'max:24'],
            '*.rim_diameter' => ['sometimes', 'numeric', 'min:10', 'max:24'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'ancho.required' => 'El ancho es obligatorio',
            'ancho.numeric' => 'El ancho debe ser numérico',
            'width.required' => 'El ancho es obligatorio',
            'width.numeric' => 'El ancho debe ser numérico',
        ];
    }
}
