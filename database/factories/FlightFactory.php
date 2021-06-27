<?php

namespace Database\Factories;

use App\Models\Flight;
use Illuminate\Database\Eloquent\Factories\Factory;

class FlightFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Flight::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'airline_id' => 1,
            'departure_airport_id' => 1,
            'arrival_airport_id' => 2,
            'is_dometic' => false,
            'notes' => $this->faker->sentence(),
            'is_archived' => false,
            'currency' => 'UKP',
            'available_after' => null
        ];
    }
}
