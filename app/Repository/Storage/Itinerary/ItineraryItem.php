<?php

namespace App\Repository\Storage\Itinerary;

use Carbon\Carbon;

class ItineraryItem
{

    /**
     * @param string|null $name
     * @param string|null $type
     * @param array<string, string> $details
     */
    public function __construct(
        public string|null $name,
        public string|null $type,
        public array $details
    ) {}
}
