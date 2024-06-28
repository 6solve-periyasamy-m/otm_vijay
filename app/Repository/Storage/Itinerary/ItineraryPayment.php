<?php

namespace App\Repository\Storage\Itinerary;

use Carbon\Carbon;

class ItineraryPayment
{
    public function __construct(
        public Carbon $made,
        public float $amount,
        public string $type,
        public string|null $customer,
    ) {}
}
