<?php

namespace Database\Factories\Quote;

use App\Models\Quote\QuoteSectionType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuoteSectionType>
 */
class QuoteSectionTypeFactory extends Factory
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
