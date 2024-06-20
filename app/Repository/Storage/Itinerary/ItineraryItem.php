<?php

namespace App\Repository\Storage\Itinerary;

use Carbon\Carbon;

class ItineraryItem
{
    /**
     * @param string $type Header title for Itinerary Item
     * @param int|null $quantity Quantity of item
     * @param Carbon|null $start Start date/time of item
     * @param Carbon|null $end End date/time of item
     * @param array<string, string> $details Extra details about the item
     */
    public function __construct(
        public string $type,
        public int|null $quantity,
        public Carbon|null $start,
        public Carbon|null $end,
        public array $details
    ) {}
}
