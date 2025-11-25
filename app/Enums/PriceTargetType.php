<?php

namespace App\Enums;

enum PriceTargetType: string
{
    case ALL_PRODUCTS = 'all_products';
    case PRODUCT = 'product';
    case BRAND = 'brand';
    case CATEGORY = 'category';
    case TIRE_SIZE = 'tire_size';
}
