<?php

namespace Database\Factories\Location;

use App\Models\Location\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Currency>
 */
class CurrencyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'code' => $this->faker->numberBetween(100, 999), // Duplicate entry possible if using letters, since it pre-seeds a load of currencies
            'symbol' => "DEMO",
        ];
    }
}
