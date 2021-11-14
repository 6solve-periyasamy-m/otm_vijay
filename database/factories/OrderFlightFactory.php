<?php

namespace Database\Factories;

use App\Models\OrderFlight;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFlightFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderFlight::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'order_customer_id' => $this->faker->numberBetween(1, 25),
        ];
    }
}
