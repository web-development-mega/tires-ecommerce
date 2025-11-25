<?php

namespace App\Filament\Resources\TireSizes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TireSizeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('width')
                    ->label('Ancho (mm)')
                    ->required()
                    ->numeric()
                    ->minValue(100)
                    ->maxValue(400),
                TextInput::make('aspect_ratio')
                    ->label('Perfil (%)')
                    ->required()
                    ->numeric()
                    ->minValue(25)
                    ->maxValue(85),
                TextInput::make('rim_diameter')
                    ->label('Diámetro de rin (")')
                    ->required()
                    ->numeric()
                    ->minValue(10)
                    ->maxValue(24),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required(),
                Toggle::make('is_active')
                    ->label('Activo')
                    ->default(true)
                    ->required(),
            ]);
    }
}
