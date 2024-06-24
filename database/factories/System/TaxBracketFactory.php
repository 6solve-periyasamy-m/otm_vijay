<?php

namespace Database\Factories\System;

use App\Models\System\TaxBracket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TaxBracket>
 */
class TaxBracketFactory extends Factory
{
    protected $model = TaxBracket::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'rate' => $this->faker->numberBetween(1, 100),
        ];
    }
}
