<?php

namespace Database\Factories\System;

use App\Models\System\Bank;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Bank>
 */
class BankFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->company,
            'address_line_1' => fake()->streetAddress,
            'town' => fake()->city,
            'region' => fake()->state,
            'country' => fake()->country,
            'postcode' => fake()->postcode
        ];
    }
}
