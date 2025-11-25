<?php

namespace App\Filament\Resources\TireSizes;

use App\Models\TireSize;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TireSizeResource extends Resource
{
    protected static ?string $model = TireSize::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-pointing-out';

    protected static ?string $recordTitleAttribute = 'label';

    public static function getNavigationGroup(): ?string
    {
        return 'Catalog';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Tire size')
                ->columns(4)
                ->schema([
                    Forms\Components\TextInput::make('label')
                        ->label('Label')
                        ->required()
                        ->maxLength(50)
                        ->helperText('Example: 205/55R16'),

                    Forms\Components\TextInput::make('width')
                        ->label('Width')
                        ->numeric()
                        ->required(),

                    Forms\Components\TextInput::make('aspect_ratio')
                        ->label('Aspect ratio')
                        ->numeric()
                        ->required(),

                    Forms\Components\TextInput::make('rim_diameter')
                        ->label('Rim')
                        ->numeric()
                        ->required(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('width')
                    ->sortable(),

                Tables\Columns\TextColumn::make('aspect_ratio')
                    ->label('Profile')
                    ->sortable(),

                Tables\Columns\TextColumn::make('rim_diameter')
                    ->label('Rim')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('label');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTireSizes::route('/'),
            'create' => Pages\CreateTireSize::route('/create'),
            'edit' => Pages\EditTireSize::route('/{record}/edit'),
        ];
    }
}
