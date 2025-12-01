<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BrandForm
{
  public static function configure(Schema $schema): Schema
  {
    return $schema
      ->components([
        TextInput::make('name')
          ->label('Nombre')
          ->required(),
        TextInput::make('slug')
          ->label('Slug')
          ->required(),
        TextInput::make('website_url')
          ->label('Sitio web')
          ->url(),
        TextInput::make('country')
          ->label('País'),
        FileUpload::make('image_url')
          ->label('Logo de la Marca')
          ->image()
          ->disk('cloudinary')
          ->directory('brands')
          ->visibility('public')
          ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
          ->maxSize(2048)
          ->imagePreviewHeight('250')
          ->helperText('Formatos aceptados: JPG, PNG, WebP, SVG. Tamaño máximo: 2MB'),
        Toggle::make('is_active')
          ->label('Activo')
          ->default(true)
          ->required(),
      ]);
  }
}
