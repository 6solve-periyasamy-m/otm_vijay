<?php

namespace App\Models\Helper\Enum;

enum AddressParent: string
{
    case CUSTOMER = 'customer';
    case ACCOMMODATION = 'accommodation';
    case ACTIVITY = 'activity';
    case AIRPORT = 'airport';
    case TRANSPORT = 'transport';
    case BRAND = 'brand';
    case ORGANIZATION = 'organization';
    case OTHER = 'other';
    case SUPPLIER = 'supplier';
}
