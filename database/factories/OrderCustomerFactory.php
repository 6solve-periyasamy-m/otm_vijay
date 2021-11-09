<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\OrderCustomer;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderCustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderCustomer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'customer_id' => $this->faker->numberBetween(1, 5), // Hack method. We know that 5 will be generated before this
            'tour_cost' => $this->faker->numberBetween(250, 750),
            'single_occupancy_surcharge' => $this->faker->numberBetween(100, 250),
            'travel_insurer' => $this->faker->company,
            'policy_number' => 'EB' . $this->faker->numberBetween(1000000, 99999999)
        ];
    }
}
