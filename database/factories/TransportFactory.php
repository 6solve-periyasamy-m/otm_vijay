<?php

namespace Database\Factories;

use App\Models\Transport;
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
            'arrival_location_id' => $this->faker->numberBetween(1, 6),
            'departure_location_id' => $this->faker->numberBetween(1, 6),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence,
            'currency' => 'GBP',
            'is_domestic' => true,
            'notes' => $this->faker->sentence,
        ];
    }
}
