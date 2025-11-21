<?php

namespace App\Enums;

enum PriceAdjustmentType: string
{
    case PERCENTAGE   = 'percentage';    // X% descuento
    case FIXED_AMOUNT = 'fixed_amount';  // -$X
    case FIXED_PRICE  = 'fixed_price';   // precio final = X
}
