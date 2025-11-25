<?php

namespace App\Filament\Resources\Brands\Pages;

use App\Filament\Resources\Brands\BrandResource;
use App\Imports\BrandsImport;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListBrands extends ListRecords
{
    protected static string $resource = BrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('importar')
                ->label('Importar desde Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->form([
                    FileUpload::make('archivo')
                        ->label('Archivo Excel/CSV')
                        ->acceptedFileTypes(['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv'])
                        ->required(),
                ])
                ->action(function (array $data) {
                    $file = storage_path('app/public/'.$data['archivo']);
                    Excel::import(new BrandsImport, $file);

                    \Filament\Notifications\Notification::make()
                        ->title('Importación exitosa')
                        ->body('Las marcas se importaron correctamente.')
                        ->success()
                        ->send();
                })
                ->modalHeading('Importar Marcas')
                ->modalDescription('Sube un archivo Excel o CSV con las columnas: nombre, slug, sitio_web, pais, activo')
                ->modalSubmitActionLabel('Importar')
                ->modalFooterActions([
                    Action::make('descargar_plantilla')
                        ->label('Descargar plantilla de ejemplo')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('gray')
                        ->url(asset('storage/plantillas/marcas-ejemplo.csv'), shouldOpenInNewTab: true),
                ]),
            CreateAction::make(),
        ];
    }
}
