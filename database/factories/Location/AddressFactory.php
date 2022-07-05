<?php

namespace Database\Factories\Location;

use App\Models\Location\Address;
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
            'name' => $this->faker->word,
            'address_parent_id' => 1,
            'address_line_1' => $this->faker->streetAddress,
            'town' => $this->faker->city,
            'region' => $this->faker->state,
            'country_id' => 1,
            'postcode' => $this->faker->postcode
        ];
    }
}
