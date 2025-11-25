<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case IN_PROCESS = 'in_process';
    case APPROVED = 'approved';
    case DECLINED = 'declined';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';
    case ERROR = 'error';
}
