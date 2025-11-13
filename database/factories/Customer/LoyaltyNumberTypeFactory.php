<?php

namespace Database\Factories\Customer;

use App\Models\Customer\LoyaltyNumberType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LoyaltyNumberType>
 */
class LoyaltyNumberTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
        ];
    }
}
