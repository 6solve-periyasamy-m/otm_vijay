<?php

namespace App\Repository\Storage\Order;

use Carbon\Carbon;

class ItineraryItem
{
    /**
     * @param string $type Header title for Itinerary Item
     * @param int $quantity Quantity on the order
     * @param Carbon|null $start Start date/time of item
     * @param Carbon|null $end End date/time of item
     * @param array<string, string> $details Extra details about the item
     */
    public function __construct(
        public readonly string $type,
        public readonly int $quantity,
        public readonly Carbon|null $start,
        public readonly Carbon|null $end,
        public array $details
    ) {}
}
