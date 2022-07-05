<?php

namespace Database\Factories\Flight;

use App\Models\Flight\Flight;
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
            'is_domestic' => false,
            'notes' => $this->faker->sentence(),
            'available_from' => null
        ];
    }
}
