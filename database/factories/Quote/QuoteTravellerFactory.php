<?php

namespace Database\Factories\Quote;

use App\Models\Quote\QuoteTraveller;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuoteTraveller>
 */
class QuoteTravellerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'has_cost' => true,
            'is_travelling' => true,
        ];
    }
}
