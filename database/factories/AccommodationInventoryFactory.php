<?php

namespace Database\Factories;

use App\Models\AccommodationInventory;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccommodationInventoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AccommodationInventory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'board_type_id' => $this->faker->numberBetween(1, 4),
            'room_type_id' => $this->faker->numberBetween(1, 3),
            'check_in' => now(),
            'checked_in' => true,
            'check_out_date_time' => now(),
            'checked_out' => false,
            'fit_selectable' => true,
            'stock' => $this->faker->numberBetween(1, 10),
            'purchase_price' => $this->faker->numberBetween(10, 50),
            'sales_price' => $this->faker->numberBetween(50, 100),
            'notes' => $this->faker->sentence,
        ];
    }
}
