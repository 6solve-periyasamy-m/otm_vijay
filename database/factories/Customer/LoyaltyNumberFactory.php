<?php

namespace Database\Factories\Customer;

use App\Models\Customer\LoyaltyNumber;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LoyaltyNumber>
 */
class LoyaltyNumberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'loyalty_number' => $this->faker->randomKey,
        ];
    }
}
