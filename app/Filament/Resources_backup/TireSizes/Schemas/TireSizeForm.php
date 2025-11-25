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
                    ->required()
                    ->numeric(),
                TextInput::make('aspect_ratio')
                    ->required()
                    ->numeric(),
                TextInput::make('rim_diameter')
                    ->required()
                    ->numeric(),
                TextInput::make('slug')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
