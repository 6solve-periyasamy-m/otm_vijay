<?php

namespace Database\Factories\Transport;

use App\Models\Transport\TransportInventoryTour;
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
            'tour_component_type' => $this->faker->randomElement(['Included', 'Upgrade', 'Add-on']),
            'tour_sales_price' => $this->faker->numberBetween(50, 100),
        ];
    }
}
