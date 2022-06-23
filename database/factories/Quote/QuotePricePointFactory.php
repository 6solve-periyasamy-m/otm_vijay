<?php

namespace Database\Factories\Quote;

use App\Models\Quote\QuotePricePoint;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuotePricePoint>
 */
class QuotePricePointFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'quantity' => $this->faker->numberBetween(5, 25),
            'price_per_person' => $this->faker->numberBetween(500, 1000)
        ];
    }
}
