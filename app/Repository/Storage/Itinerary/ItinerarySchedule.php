<?php

namespace App\Repository\Storage\Itinerary;

use Carbon\Carbon;

class ItinerarySchedule
{
    public function __construct(
        public ItineraryScheduleType $type,
        public Carbon|null $due,
        public float $amount,
        public float|null $percentage,
        public bool|null $paid = null,
    ) {}
}
