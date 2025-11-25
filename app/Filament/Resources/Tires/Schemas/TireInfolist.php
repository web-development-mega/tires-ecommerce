<?php

namespace App\Filament\Resources\Tires\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TireInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('brand.name')
                    ->numeric(),
                TextEntry::make('tireSize.id')
                    ->numeric(),
                TextEntry::make('sku')
                    ->label('SKU'),
                TextEntry::make('name'),
                TextEntry::make('slug'),
                TextEntry::make('pattern'),
                TextEntry::make('load_index')
                    ->numeric(),
                TextEntry::make('speed_rating'),
                TextEntry::make('utqg_treadwear')
                    ->numeric(),
                TextEntry::make('utqg_traction'),
                TextEntry::make('utqg_temperature'),
                TextEntry::make('fuel_efficiency_grade'),
                TextEntry::make('wet_grip_grade'),
                TextEntry::make('noise_db')
                    ->numeric(),
                TextEntry::make('noise_class'),
                TextEntry::make('country_of_origin'),
                TextEntry::make('usage'),
                IconEntry::make('is_runflat')
                    ->boolean(),
                IconEntry::make('is_all_terrain')
                    ->boolean(),
                IconEntry::make('is_highway')
                    ->boolean(),
                IconEntry::make('is_winter')
                    ->boolean(),
                IconEntry::make('is_summer')
                    ->boolean(),
                TextEntry::make('base_price')
                    ->numeric(),
                TextEntry::make('sale_price')
                    ->numeric(),
                TextEntry::make('currency'),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
                TextEntry::make('deleted_at')
                    ->dateTime(),
            ]);
    }
}
