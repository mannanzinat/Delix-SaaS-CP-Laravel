<?php

namespace App\Enums;

enum UserTypeEnum: string
{
    case SUPER_ADMIN    = 'super_admin';
    case ADMIN          = 'admin';
    case DELIVERY       = 'delivery';
    case MERCHANT       = 'merchant';
    case MERCHANT_STAFF = 'merchant_staff';
    case STAFF          = 'staff';

}
