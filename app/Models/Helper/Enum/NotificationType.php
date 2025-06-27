<?php

namespace App\Models\Helper\Enum;

use App\Models\Helper\Enum\Trait\ConvertsToArray;

enum NotificationType: int
{
    use ConvertsToArray;

    case GENERIC = 0;
    case ORDER_CREATED = 1;
    case ORDER_UPDATED = 2;
    case PAYMENT_MADE = 3;
    case COMPONENTS_CHANGED = 4;
    case CUSTOMER_UPDATED = 5;
    case BOOKING_PROGRESS = 6;

}
