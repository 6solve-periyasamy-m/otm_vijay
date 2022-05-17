<?php

namespace Database\Factories\Transport;

use App\Models\Location\Address;
use App\Models\Transport\Transport;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransportFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transport::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'transport_type_id' => $this->faker->numberBetween(1, 7),
            'arrival_address_id' => Address::factory()->create()->id,
            'departure_location_id' => Address::factory()->create()->id,
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence,
            'is_domestic' => true,
            'notes' => $this->faker->sentence,
        ];
    }
}
