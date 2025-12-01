<?php

namespace App\Filament\Resources\Brands\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BrandsTable
{
  public static function configure(Table $table): Table
  {
    return $table
      ->columns([
        ImageColumn::make('image_url')
          ->label('Logo')
          ->disk('public')
          ->width(60)
          ->height(60)
          ->defaultImageUrl(asset('images/logo.svg')),
        TextColumn::make('name')
          ->label('Nombre')
          ->searchable()
          ->sortable(),
        TextColumn::make('slug')
          ->label('Slug')
          ->searchable(),
        TextColumn::make('website_url')
          ->label('Sitio web')
          ->searchable()
          ->url(fn($record) => $record->website_url)
          ->openUrlInNewTab(),
        TextColumn::make('country')
          ->label('País')
          ->searchable(),
        IconColumn::make('is_active')
          ->label('Activo')
          ->boolean(),
        TextColumn::make('created_at')
          ->label('Creado')
          ->dateTime('d/m/Y H:i')
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
        TextColumn::make('updated_at')
          ->label('Actualizado')
          ->dateTime('d/m/Y H:i')
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->filters([
        //
      ])
      ->recordActions([
        ViewAction::make(),
        EditAction::make(),
      ])
      ->toolbarActions([
        BulkActionGroup::make([
          DeleteBulkAction::make(),
        ]),
      ]);
  }
}
