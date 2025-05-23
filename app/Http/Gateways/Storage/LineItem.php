<?php

namespace App\Http\Gateways\Storage;

use App\Models\Location\Currency;

/**
 * @property-read string $name The name of the item being billed for
 * @property-read float $cost The cost of the item, as decimal
 * @property-read int $quantity The quantity of the item being sold (defaults to 1)
 */
class LineItem
{
    /**
     * @param string $name The name of the item being billed for
     * @param float $cost The cost of the item, as decimal
     * @param int $quantity The quantity of the item being sold (defaults to 1)
     */
    public function __construct(
        public readonly string $name,
        public readonly float $cost,
        public readonly int $quantity = 1) { }

    public function toStripe(Currency|string|null $currency = null): array
    {
        if ($currency instanceof Currency) {
            $currency = $currency->code;
        }
        $currency = $currency ?? config('app.currency');
        return [
            'price_data' => [
                'currency' => $currency,
                'product_data' => [
                    'name' => $this->name,
                ],
                'unit_amount' => round($this->cost * 100),
            ],
            'quantity' => $this->quantity,
        ];
    }
}
