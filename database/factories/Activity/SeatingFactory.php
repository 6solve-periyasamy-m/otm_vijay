<?php

namespace Database\Factories\Activity;

use App\Models\Activity\Seating;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Seating>
 */
class SeatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->streetName(),
            'description' => $this->faker->sentence(),
        ];
    }
}
