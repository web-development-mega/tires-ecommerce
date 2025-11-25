<?php

namespace App\Enums;

enum OrderDeliveryType: string
{
    case HOME_DELIVERY = 'home_delivery';
    case SERVICE_LOCATION = 'service_location'; // workshop / serviteca
    case PICKUP = 'pickup';           // store pickup (optional for future)
}
