<?php

namespace App\Enums;

enum CompanyContractStatus: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case SUSPENDED = 'suspended';
    case EXPIRED = 'expired';
    case TERMINATED = 'terminated';
}
