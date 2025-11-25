<?php

namespace App\Filament\Resources\Tires;

use App\Models\Tire;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TireResource extends Resource
{
    protected static ?string $model = Tire::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): ?string
    {
        return 'Catalog';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Basic info')
                ->columns(3)
                ->schema([
                    Forms\Components\TextInput::make('sku')
                        ->label('SKU')
                        ->required()
                        ->maxLength(100),

                    Forms\Components\TextInput::make('name')
                        ->label('Name')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('slug')
                        ->label('Slug')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('pattern')
                        ->label('Pattern')
                        ->maxLength(255),

                    Forms\Components\Select::make('usage')
                        ->label('Usage')
                        ->options([
                            'car' => 'Car',
                            'suv' => 'SUV',
                            'truck' => 'Truck',
                            'moto' => 'Motorcycle',
                            'van' => 'Van',
                            'bus' => 'Bus',
                            'otr' => 'OTR',
                        ])
                        ->native(false),

                    Forms\Components\Select::make('brand_id')
                        ->label('Brand')
                        ->relationship('brand', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\Select::make('tire_size_id')
                        ->label('Size')
                        ->relationship('tireSize', 'label')
                        ->searchable()
                        ->preload()
                        ->required(),
                ]),

            Forms\Components\Section::make('Specs')
                ->columns(3)
                ->schema([
                    Forms\Components\TextInput::make('load_index')
                        ->label('Load index')
                        ->numeric(),

                    Forms\Components\TextInput::make('speed_rating')
                        ->label('Speed rating')
                        ->maxLength(5),

                    Forms\Components\TextInput::make('country_of_origin')
                        ->label('Country of origin')
                        ->maxLength(2)
                        ->helperText('ISO code, e.g. BR, CO, MX'),
                ]),

            Forms\Components\Section::make('UTQG')
                ->columns(3)
                ->schema([
                    Forms\Components\TextInput::make('utqg_treadwear')
                        ->label('Treadwear')
                        ->numeric(),

                    Forms\Components\TextInput::make('utqg_traction')
                        ->label('Traction')
                        ->maxLength(5),

                    Forms\Components\TextInput::make('utqg_temperature')
                        ->label('Temperature')
                        ->maxLength(5),
                ]),

            Forms\Components\Section::make('Label / EU')
                ->columns(4)
                ->schema([
                    Forms\Components\TextInput::make('fuel_efficiency_grade')
                        ->label('Fuel efficiency')
                        ->maxLength(5),

                    Forms\Components\TextInput::make('wet_grip_grade')
                        ->label('Wet grip')
                        ->maxLength(5),

                    Forms\Components\TextInput::make('noise_db')
                        ->label('Noise (dB)')
                        ->numeric(),

                    Forms\Components\TextInput::make('noise_class')
                        ->label('Noise class')
                        ->maxLength(5),
                ]),

            Forms\Components\Section::make('Flags')
                ->columns(5)
                ->schema([
                    Forms\Components\Toggle::make('is_runflat')
                        ->label('Runflat'),

                    Forms\Components\Toggle::make('is_all_terrain')
                        ->label('All terrain'),

                    Forms\Components\Toggle::make('is_highway')
                        ->label('Highway'),

                    Forms\Components\Toggle::make('is_winter')
                        ->label('Winter'),

                    Forms\Components\Toggle::make('is_summer')
                        ->label('Summer'),
                ]),

            Forms\Components\Section::make('Pricing & stock')
                ->columns(4)
                ->schema([
                    Forms\Components\TextInput::make('base_price')
                        ->label('Base price')
                        ->numeric()
                        ->required()
                        ->prefix('COP'),

                    Forms\Components\TextInput::make('sale_price')
                        ->label('Sale price')
                        ->numeric()
                        ->prefix('COP'),

                    Forms\Components\TextInput::make('effective_price')
                        ->label('Effective price')
                        ->numeric()
                        ->prefix('COP')
                        ->helperText('Set manually for now; can be automated later.'),

                    Forms\Components\TextInput::make('stock_qty')
                        ->label('Stock quantity')
                        ->numeric()
                        ->minValue(0),

                    Forms\Components\Toggle::make('is_in_stock')
                        ->label('In stock')
                        ->default(true),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sku')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->limit(40)
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('brand.name')
                    ->label('Brand')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('tireSize.label')
                    ->label('Size')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('effective_price')
                    ->label('Price')
                    ->money('COP', true)
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_in_stock')
                    ->label('In stock')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_all_terrain')
                    ->label('AT')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_in_stock'),
                Tables\Filters\SelectFilter::make('brand_id')
                    ->relationship('brand', 'name')
                    ->label('Brand'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTires::route('/'),
            'create' => Pages\CreateTire::route('/create'),
            'edit' => Pages\EditTire::route('/{record}/edit'),
        ];
    }
}
