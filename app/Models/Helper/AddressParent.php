<?php

namespace App\Models\Helper;

enum AddressParent: string
{
    case CUSTOMER = 'customer';
    case ACCOMMODATION = 'accommodation';
    case ACTIVITY = 'activity';
    case AIRPORT = 'airport';
    case TRANSPORT = 'transport';
    case BRAND = 'brand';
    case OTHER = 'other';
}
