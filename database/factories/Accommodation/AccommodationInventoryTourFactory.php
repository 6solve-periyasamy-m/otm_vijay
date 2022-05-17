<?php

namespace Database\Factories\Accommodation;

use App\Models\Accommodation\AccommodationInventoryTour;
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
            'tour_component_type' => $this->faker->randomElement(['Included', 'Upgrade', 'Add-on']),
            'tour_sales_price' => $this->faker->numberBetween(50, 100),
        ];
    }
}
