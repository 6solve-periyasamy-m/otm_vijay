<?php

namespace Database\Factories\Flight;

use App\Models\Flight\Airline;
use Illuminate\Database\Eloquent\Factories\Factory;

class AirlineFactory extends Factory
{
    protected $model = Airline::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
        ];
    }
}
