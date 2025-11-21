<?php

namespace App\Enums;

enum CompanyType: string
{
    case FLEET      = 'fleet';
    case CORPORATE  = 'corporate';
    case DEALER     = 'dealer';
    case OTHER      = 'other';
}
