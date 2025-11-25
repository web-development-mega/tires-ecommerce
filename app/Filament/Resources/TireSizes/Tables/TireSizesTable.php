<?php

namespace App\Filament\Resources\TireSizes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TireSizesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('width')
                    ->label('Ancho')
                    ->numeric()
                    ->sortable()
                    ->suffix(' mm'),
                TextColumn::make('aspect_ratio')
                    ->label('Perfil')
                    ->numeric()
                    ->sortable()
                    ->suffix('%'),
                TextColumn::make('rim_diameter')
                    ->label('Diámetro')
                    ->numeric()
                    ->sortable()
                    ->suffix('"'),
                TextColumn::make('slug')
                    ->label('Identificador')
                    ->searchable()
                    ->copyable(),
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
