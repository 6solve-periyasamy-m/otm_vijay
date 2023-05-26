<?php

namespace Database\Factories\Order;

use App\Models\Order\Order;
use App\Models\Tour\Tour;
use Illuminate\Database\Eloquent\Factories\Factory;
use function now;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        Tour::factory()->create();
        return [
            'tour_id' => Tour::factory()->create()->id,
            'ordered_on' => now(),
            'booking_reference' => 'OCT' . $this->faker->numberBetween(100000, 999999),
        ];
    }
}
