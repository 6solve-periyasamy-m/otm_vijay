<?php

namespace Database\Factories\Flight;

use App\Models\Flight\FlightInventoryTour;
use Illuminate\Database\Eloquent\Factories\Factory;

class FlightInventoryTourFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FlightInventoryTour::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'tour_component_type' => $this->faker->randomElement(['Included', 'Upgrade', 'Add-on']),
            'tour_sales_price' => $this->faker->numberBetween(50, 100),
            'flight_type' => $this->faker->randomElement(['Outbound', 'Inbound',]),
        ];
    }
}
