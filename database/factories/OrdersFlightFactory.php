<?php

namespace Database\Factories;

use App\Models\OrdersFlight;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrdersFlightFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrdersFlight::class;

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
