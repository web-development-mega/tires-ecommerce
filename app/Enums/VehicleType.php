<?php

namespace App\Enums;

enum VehicleType: string
{
    case CAR = 'car';
    case SUV = 'suv';
    case PICKUP = 'pickup';
    case VAN = 'van';
    case TRUCK = 'truck';
    case BUS = 'bus';
    case MOTORCYCLE = 'motorcycle';
    case ATV = 'atv';
    case UTV = 'utv';
    case BICYCLE = 'bicycle';
    case OTHER = 'other';
}
