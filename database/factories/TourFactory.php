<?php

namespace Database\Factories;

use App\Models\Tour;
use Illuminate\Database\Eloquent\Factories\Factory;

class TourFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tour::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => 1,
            'event_id' => 1,
            'name' => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'notes' => $this->faker->sentence,
            'base_price_per_person' => 1000,
            'margin' => 500,
            'single_occupancy_surcharge' => 100,
            'deposit' => 100,
            'stock_control_active' => true,
            'booking_form_url' => 'example-tour',
            'tour_category_id' => null,
            'tour_merchandise_id' => null,
            'is_active' => true,
            'date_from' => '2022-05-01',
            'date_to' => '2022-05-31'
        ];
    }
}
