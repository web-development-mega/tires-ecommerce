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
          ->label('Marca')
          ->numeric(),
        TextEntry::make('tireSize.id')
          ->label('ID Tamaño')
          ->numeric(),
        TextEntry::make('sku')
          ->label('SKU'),
        TextEntry::make('name')
          ->label('Nombre'),
        TextEntry::make('slug')
          ->label('Slug'),
        TextEntry::make('pattern')
          ->label('Patrón'),
        TextEntry::make('load_index')
          ->label('Índice de carga')
          ->numeric(),
        TextEntry::make('speed_rating')
          ->label('Clasificación de velocidad'),
        TextEntry::make('utqg_treadwear')
          ->label('UTQG desgaste')
          ->numeric(),
        TextEntry::make('utqg_traction')
          ->label('UTQG tracción'),
        TextEntry::make('utqg_temperature')
          ->label('UTQG temperatura'),
        TextEntry::make('fuel_efficiency_grade')
          ->label('Eficiencia de combustible'),
        TextEntry::make('wet_grip_grade')
          ->label('Agarre en mojado'),
        TextEntry::make('noise_db')
          ->label('Ruido dB')
          ->numeric(),
        TextEntry::make('noise_class')
          ->label('Clase de ruido'),
        TextEntry::make('country_of_origin')
          ->label('País de origen'),
        TextEntry::make('usage')
          ->label('Uso'),
        IconEntry::make('is_runflat')
          ->label('Es runflat')
          ->boolean(),
        IconEntry::make('is_all_terrain')
          ->label('Es todo terreno')
          ->boolean(),
        IconEntry::make('is_highway')
          ->label('Es carretera')
          ->boolean(),
        IconEntry::make('is_winter')
          ->label('Es invierno')
          ->boolean(),
        IconEntry::make('is_summer')
          ->label('Es verano')
          ->boolean(),
        TextEntry::make('base_price')
          ->label('Precio base')
          ->numeric(),
        TextEntry::make('sale_price')
          ->label('Precio de venta')
          ->numeric(),
        TextEntry::make('currency')
          ->label('Moneda'),
        IconEntry::make('is_active')
          ->label('Activo')
          ->boolean(),
        TextEntry::make('created_at')
          ->label('Creado')
          ->dateTime(),
        TextEntry::make('updated_at')
          ->label('Actualizado')
          ->dateTime(),
        TextEntry::make('deleted_at')
          ->label('Eliminado')
          ->dateTime(),
      ]);
  }
}
