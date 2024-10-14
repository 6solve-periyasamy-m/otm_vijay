<?php

namespace App\Models\Helper\Enum;

enum BookingTravellerRole: int
{
    case NORMAL = 1;
    case UNKNOWN = 2;
    case NOT_TRAVELLING = 3;
}
