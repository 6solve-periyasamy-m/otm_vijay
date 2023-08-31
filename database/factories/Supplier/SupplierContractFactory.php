<?php

namespace Database\Factories\Supplier;

use App\Models\Location\Currency;
use App\Models\Supplier\SupplierContract;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SupplierContract>
 */
class SupplierContractFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $currency = Currency::all()->first() ?? Currency::create(['name' => 'Test Currency', 'code' => 'TCS', 'symbol' => '$']);
        return [
            'purchase_order_number' => 'SUP' . fake()->randomNumber(12, true),
            'currency_id' => $currency->id,
            'agreed_exchange' =>(fake()->numberBetween(500, 1500) / 100),
            'total_cost' => fake()->randomNumber(8),
            'price_per_item' => fake()->randomNumber(6),
            'confirmed' => fake()->boolean,
            'notes' => fake()->text,
        ];
    }
}
