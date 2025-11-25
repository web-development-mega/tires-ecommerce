<?php

namespace App\Filament\Resources\Tires\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TiresTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('brand.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tireSize.id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('pattern')
                    ->searchable(),
                TextColumn::make('load_index')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('speed_rating')
                    ->searchable(),
                TextColumn::make('utqg_treadwear')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('utqg_traction')
                    ->searchable(),
                TextColumn::make('utqg_temperature')
                    ->searchable(),
                TextColumn::make('fuel_efficiency_grade')
                    ->searchable(),
                TextColumn::make('wet_grip_grade')
                    ->searchable(),
                TextColumn::make('noise_db')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('noise_class')
                    ->searchable(),
                TextColumn::make('country_of_origin')
                    ->searchable(),
                TextColumn::make('usage')
                    ->searchable(),
                IconColumn::make('is_runflat')
                    ->boolean(),
                IconColumn::make('is_all_terrain')
                    ->boolean(),
                IconColumn::make('is_highway')
                    ->boolean(),
                IconColumn::make('is_winter')
                    ->boolean(),
                IconColumn::make('is_summer')
                    ->boolean(),
                TextColumn::make('base_price')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sale_price')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('currency')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
