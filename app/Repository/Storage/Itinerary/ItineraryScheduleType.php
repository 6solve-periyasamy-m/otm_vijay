<?php

namespace App\Repository\Storage\Itinerary;

use App\Models\Helper\Enum\Trait\ConvertsToArray;

enum ItineraryScheduleType: int
{
    use ConvertsToArray;

    case DEPOSIT = 1;
    case BOOKING_FEE = 2;
    case INSTALLMENT = 3;
    case REMAINING = 4;
}
