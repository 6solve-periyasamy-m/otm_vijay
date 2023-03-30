<?php

namespace Database\Factories\Order;

use App\Models\Customer\Customer;
use App\Models\Order\OrderCustomer;
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
        $customer = Customer::factory()->create();
        return [
            'customer_id' => $customer->id, // Hack method. We know that 5 will be generated before this
            'tour_cost' => $this->faker->numberBetween(250, 750),
            'single_occupancy_surcharge' => $this->faker->numberBetween(100, 250),
            'travel_insurer' => $this->faker->company,
            'policy_number' => 'EB' . $this->faker->numberBetween(1000000, 99999999)
        ];
    }
}
