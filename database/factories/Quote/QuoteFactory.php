<?php

namespace Database\Factories\Quote;

use App\Models\Quote\Quote;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Quote>
 */
class QuoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Quote::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'reference' => 'OTMQ' . $this->faker->numberBetween(100000000000, 999999999999) . substr(str_shuffle(str_repeat($x = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(4 / strlen($x)))), 1, 4),
            'deposit' => $this->faker->numberBetween(100, 300),
            'expires' => $this->faker->dateTimeBetween('now', '+6 months'),
        ];
    }
}
