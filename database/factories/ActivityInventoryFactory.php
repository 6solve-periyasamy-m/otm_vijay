<?php

namespace Database\Factories;

use App\Models\ActivityInventory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityInventoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ActivityInventory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ticket_type_id' => $this->faker->numberBetween(1, 4),
            'starts_at' => now(),
            'ends_at' => now(),
            'fit_selectable' => true,
            'stock' => $this->faker->numberBetween(1, 10),
            'purchase_price' => $this->faker->numberBetween(10, 50),
            'sales_price' => $this->faker->numberBetween(50, 100),
            'currency' => 'GBP',
            'notes' => $this->faker->sentence,
        ];
    }
}
