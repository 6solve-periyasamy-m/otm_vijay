<?php

namespace App\Repository\Storage\Itinerary;

use Carbon\Carbon;

class ItinerarySchedule
{
    public function __construct(
        public Carbon|null $due,
        public float $amount,
        public bool|null $paid = null,
    ) {}
}
