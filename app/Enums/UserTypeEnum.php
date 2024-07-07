<?php

namespace App\Enums;

enum UserTypeEnum: string
{
    case SAS_ADMIN              = 'sas_admin';
    case SAS_ADMIN_STAFF        = 'sas_admin_staff';
    case COURIER_ADMIN          = 'courier_admin';
    case COURIER_ADMIN_STAFF    = 'courier_admin_staff';
    case DELIVERY               = 'delivery';
    case MERCHANT               = 'merchant';
    case MERCHANT_STAFF         = 'merchant_staff';

}
