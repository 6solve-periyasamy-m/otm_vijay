<?php

namespace Database\Factories\Flight;

use App\Models\Flight\FlightInventory;
use Illuminate\Database\Eloquent\Factories\Factory;

class FlightInventoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FlightInventory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'check_in' => now(),
            'departs_at' => now()->addHour(),
            'arrives_at' => now()->addHours(10),
            'flight_number' => $this->faker->word,
            'fit_selectable' => 1,
            'stock' => 5,
            'purchase_price' => 200,
            'sales_price' => 300,
            'notes' => $this->faker->sentence
        ];
    }
}
