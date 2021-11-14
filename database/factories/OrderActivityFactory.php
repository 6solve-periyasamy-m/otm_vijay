<?php

namespace Database\Factories;

use App\Models\OrderActivity;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderActivityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderActivity::class;

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
