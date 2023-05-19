<?php

namespace Database\Factories\Tour;

use App\Models\Tour\Tour;
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
            'date_from' => now()->addDays(5),
            'date_to' => now()->addDays(30),
            'terms' => 'Default Terms',
            'final_payment' => now()->addDays(5),
        ];
    }
}
