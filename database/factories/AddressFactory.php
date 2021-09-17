<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'address_line_1' => $this->faker->streetAddress,
            'town' => $this->faker->city,
            'region' => $this->faker->state,
            'country' => $this->faker->country,
            'postcode' => $this->faker->postcode
        ];
    }
}
