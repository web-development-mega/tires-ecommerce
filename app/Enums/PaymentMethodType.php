<?php

namespace App\Enums;

enum PaymentMethodType: string
{
    case CARD      = 'card';
    case PSE       = 'pse';
    case NEQUI     = 'nequi';
    case DAVIPLATA = 'daviplata';
    case CASH      = 'cash';
    case OTHER     = 'other';
}
