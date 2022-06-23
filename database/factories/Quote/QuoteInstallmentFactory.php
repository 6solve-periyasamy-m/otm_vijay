<?php

namespace Database\Factories\Quote;

use App\Models\Quote\QuoteInstallment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuoteInstallment>
 */
class QuoteInstallmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'due_on' => $this->faker->dateTimeBetween('now', '+6 months'),
            'amount' => $this->faker->numberBetween(100, 300),
        ];
    }
}
