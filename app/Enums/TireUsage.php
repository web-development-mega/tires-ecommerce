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
}
