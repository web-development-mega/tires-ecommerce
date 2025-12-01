<?php

namespace App\Filament\Resources\Tires\Schemas;

use App\Enums\TireUsage;
use Filament\Forms\Components\FileUpload;
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
          ->label('Marca')
          ->relationship('brand', 'name')
          ->required()
          ->searchable()
          ->preload(),
        Select::make('tire_size_id')
          ->label('Tamaño')
          ->relationship('tireSize', 'id')
          ->required()
          ->searchable()
          ->preload(),
        TextInput::make('sku')
          ->label('SKU')
          ->required()
          ->maxLength(255)
          ->unique(ignoreRecord: true),
        TextInput::make('name')
          ->label('Nombre')
          ->required()
          ->maxLength(255),
        TextInput::make('slug')
          ->label('Slug')
          ->required()
          ->maxLength(255)
          ->unique(ignoreRecord: true),
        TextInput::make('pattern')
          ->label('Patrón/Diseño')
          ->maxLength(255),
        FileUpload::make('image_url')
          ->label('Imagen de la Llanta')
          ->image()
          ->disk('cloudinary')
          ->directory('tires')
          ->visibility('public')
          ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
          ->maxSize(2048)
          ->imagePreviewHeight('250')
          ->helperText('Formatos aceptados: JPG, PNG, WebP. Tamaño máximo: 2MB'),
        TextInput::make('load_index')
          ->label('Índice de Carga')
          ->numeric()
          ->minValue(0)
          ->maxValue(300),
        TextInput::make('speed_rating')
          ->label('Índice de Velocidad')
          ->maxLength(10),
        TextInput::make('utqg_treadwear')
          ->label('UTQG - Desgaste')
          ->numeric()
          ->minValue(0),
        TextInput::make('utqg_traction')
          ->label('UTQG - Tracción')
          ->maxLength(10),
        TextInput::make('utqg_temperature')
          ->label('UTQG - Temperatura')
          ->maxLength(10),
        TextInput::make('fuel_efficiency_grade')
          ->label('Eficiencia de Combustible')
          ->maxLength(10),
        TextInput::make('wet_grip_grade')
          ->label('Agarre en Mojado')
          ->maxLength(10),
        TextInput::make('noise_db')
          ->label('Ruido (dB)')
          ->numeric()
          ->minValue(0)
          ->maxValue(150)
          ->suffix('dB'),
        TextInput::make('noise_class')
          ->label('Clase de Ruido')
          ->maxLength(10),
        TextInput::make('country_of_origin')
          ->label('País de Origen')
          ->maxLength(255),
        Select::make('usage')
          ->label('Uso')
          ->options(TireUsage::class)
          ->searchable(),
        Toggle::make('is_runflat')
          ->label('Run Flat')
          ->default(false),
        Toggle::make('is_all_terrain')
          ->label('Todo Terreno')
          ->default(false),
        Toggle::make('is_highway')
          ->label('Carretera')
          ->default(false),
        Toggle::make('is_winter')
          ->label('Invierno')
          ->default(false),
        Toggle::make('is_summer')
          ->label('Verano')
          ->default(false),
        TextInput::make('base_price')
          ->label('Precio Base')
          ->required()
          ->numeric()
          ->minValue(0)
          ->prefix('$'),
        TextInput::make('sale_price')
          ->label('Precio de Venta')
          ->numeric()
          ->minValue(0)
          ->prefix('$'),
        TextInput::make('currency')
          ->label('Moneda')
          ->required()
          ->default('COP')
          ->maxLength(10),
        Toggle::make('is_active')
          ->label('Activo')
          ->default(true),
      ]);
  }
}
