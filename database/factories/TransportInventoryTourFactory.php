<?php

namespace Database\Factories;

use App\Models\TransportInventoryTour;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransportInventoryTourFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TransportInventoryTour::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'tour_id' => 1,
            'tour_component_type' => $this->faker->numberBetween(1, 3),
            'tour_sales_price' => $this->faker->numberBetween(50, 100),
        ];
    }
}
