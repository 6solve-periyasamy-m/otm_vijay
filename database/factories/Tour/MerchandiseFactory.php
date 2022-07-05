<?php

namespace Database\Factories\Tour;

use App\Models\Tour\Merchandise;
use Illuminate\Database\Eloquent\Factories\Factory;

class MerchandiseFactory extends Factory
{
    protected $model = Merchandise::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => implode(' ', $this->faker->words),
            'purchase_price' => $this->faker->numberBetween(10, 100),
            'tour_sales_price' => $this->faker->numberBetween(10, 100),
            'stock' => $this->faker->numberBetween(10, 100),
            'tour_component_type' => $this->faker->randomElement(['Included', 'Add-on']),
        ];
    }
}
