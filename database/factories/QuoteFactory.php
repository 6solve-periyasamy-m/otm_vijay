<?php

namespace Database\Factories;

use App\Models\Quote;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'tour_id' => 1,
            'customer_id' => $this->faker->numberBetween(1, 5),
            'is_converted' => true,
            'pax_number' => $this->faker->numberBetween(1, 1000000),
            'total_quote_value' => $this->faker->numberBetween(1, 10000),
        ];
    }
}
