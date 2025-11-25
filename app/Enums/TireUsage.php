<?php

namespace App\Enums;

enum TireUsage: string
{
    case PASSENGER = 'passenger';
    case SUV = 'suv';
    case LIGHT_TRUCK = 'light_truck';
    case TRUCK_BUS = 'truck_bus';
    case MOTORCYCLE = 'motorcycle';
    case BICYCLE = 'bicycle';
    case OTR = 'otr';
    case INDUSTRIAL = 'industrial';

    public function getLabel(): string
    {
        return match ($this) {
            self::PASSENGER => 'Automóvil',
            self::SUV => 'SUV',
            self::LIGHT_TRUCK => 'Camioneta',
            self::TRUCK_BUS => 'Camión/Bus',
            self::MOTORCYCLE => 'Motocicleta',
            self::BICYCLE => 'Bicicleta',
            self::OTR => 'OTR (Off-The-Road)',
            self::INDUSTRIAL => 'Industrial',
        };
    }
}
