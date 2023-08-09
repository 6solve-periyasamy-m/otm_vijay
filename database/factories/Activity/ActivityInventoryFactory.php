<?php

namespace Database\Factories\Activity;

use App\Models\Activity\ActivityInventory;
use Illuminate\Database\Eloquent\Factories\Factory;
use function now;

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
            'starts_at' => now(),
            'ends_at' => now(),
            'fit_selectable' => true,
            'stock' => $this->faker->numberBetween(1, 10),
            'purchase_price' => $this->faker->numberBetween(10, 50),
            'sales_price' => $this->faker->numberBetween(50, 100),
            'internal_notes' => $this->faker->sentence,
        ];
    }
}
