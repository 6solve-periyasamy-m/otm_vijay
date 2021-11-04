<?php

namespace Database\Factories;

use App\Models\TransportInventory;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransportInventoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TransportInventory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'travel_class_id' => $this->faker->numberBetween(1, 2),
            'departs_at' => now(),
            'departure_time_confirmed' => true,
            'arrives_at' => now(),
            'arrival_time_confirmed' => false,
            'fit_selectable' => true,
            'stock' => $this->faker->numberBetween(1, 10),
            'purchase_price' => $this->faker->numberBetween(10, 50),
            'sales_price' => $this->faker->numberBetween(50, 100),
            'notes' => $this->faker->sentence,
        ];
    }
}
