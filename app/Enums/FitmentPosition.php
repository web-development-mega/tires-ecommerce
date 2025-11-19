<?php

namespace App\Enums;

enum FitmentPosition: string
{
    case FRONT = 'front';
    case REAR = 'rear';
    case BOTH = 'both';
    case SPARE = 'spare';
}
