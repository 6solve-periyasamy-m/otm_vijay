<?php

namespace Database\Factories;

use App\Models\AccommodationInventoryTour;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccommodationInventoryTourFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AccommodationInventoryTour::class;

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
