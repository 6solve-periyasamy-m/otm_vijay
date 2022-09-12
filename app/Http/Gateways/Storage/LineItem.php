<?php

namespace App\Http\Gateways\Storage;

/**
 * @property-read string $name The name of the item being billed for
 * @property-read float $cost The cost of the item
 * @property-read int $quantity The quantity of the item being sold (defaults to 1)
 */
class LineItem
{
    /**
     * @param string $name The name of the item being billed for
     * @param float $cost The cost of the item, in full
     * @param int $quantity The quantity of the item being sold (defaults to 1)
     */
    public function __construct(
        public readonly string $name,
        public readonly float $cost,
        public readonly int $quantity = 1) { }
}
