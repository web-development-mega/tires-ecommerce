<?php

namespace App\Filament\Resources\Tires;

use App\Filament\Resources\Tires\Pages\CreateTire;
use App\Filament\Resources\Tires\Pages\EditTire;
use App\Filament\Resources\Tires\Pages\ListTires;
use App\Filament\Resources\Tires\Pages\ViewTire;
use App\Filament\Resources\Tires\Schemas\TireForm;
use App\Filament\Resources\Tires\Schemas\TireInfolist;
use App\Filament\Resources\Tires\Tables\TiresTable;
use App\Models\Tire;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TireResource extends Resource
{
    protected static ?string $model = Tire::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Llantas';

    protected static ?string $modelLabel = 'Llanta';

    protected static ?string $pluralModelLabel = 'Llantas';

    public static function getNavigationGroup(): ?string
    {
        return 'Catálogo';
    }

    public static function form(Schema $schema): Schema
    {
        return TireForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TireInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TiresTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTires::route('/'),
            'create' => CreateTire::route('/create'),
            'view' => ViewTire::route('/{record}'),
            'edit' => EditTire::route('/{record}/edit'),
        ];
    }
}
