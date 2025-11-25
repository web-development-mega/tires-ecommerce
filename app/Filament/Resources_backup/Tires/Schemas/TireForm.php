<?php

namespace App\Filament\Resources\Tires\Schemas;

use App\Enums\TireUsage;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TireForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('brand_id')
                    ->relationship('brand', 'name')
                    ->required(),
                Select::make('tire_size_id')
                    ->relationship('tireSize', 'id')
                    ->required(),
                TextInput::make('sku')
                    ->label('SKU')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('pattern'),
                TextInput::make('load_index')
                    ->numeric(),
                TextInput::make('speed_rating'),
                TextInput::make('utqg_treadwear')
                    ->numeric(),
                TextInput::make('utqg_traction'),
                TextInput::make('utqg_temperature'),
                TextInput::make('fuel_efficiency_grade'),
                TextInput::make('wet_grip_grade'),
                TextInput::make('noise_db')
                    ->numeric(),
                TextInput::make('noise_class'),
                TextInput::make('country_of_origin'),
                Select::make('usage')
                    ->options(TireUsage::class),
                Toggle::make('is_runflat')
                    ->required(),
                Toggle::make('is_all_terrain')
                    ->required(),
                Toggle::make('is_highway')
                    ->required(),
                Toggle::make('is_winter')
                    ->required(),
                Toggle::make('is_summer')
                    ->required(),
                TextInput::make('base_price')
                    ->required()
                    ->numeric(),
                TextInput::make('sale_price')
                    ->numeric(),
                TextInput::make('currency')
                    ->required()
                    ->default('COP'),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
