<?php

namespace Database\Factories\Flight;

use App\Models\Flight\Airport;
use App\Models\Location\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class AirportFactory extends Factory
{
    protected $model = Airport::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $address = Address::factory()->create();
        return [
            'name' => $this->faker->word,
            'iata_code' => $this->faker->randomLetter * 3,
            'address_id' => $address->id,
        ];
    }
}
