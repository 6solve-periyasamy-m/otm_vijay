<?php

namespace Database\Factories\Accommodation;

use App\Models\Accommodation\AccommodationInventory;
use Illuminate\Database\Eloquent\Factories\Factory;
use function now;

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
            'board_type_id' => 1,
            'room_type_id' => 1,
            'check_in' => now(),
            'check_in_time_confirmed' => true,
            'check_out' => now(),
            'check_out_time_confirmed' => false,
            'fit_selectable' => true,
            'stock' => $this->faker->numberBetween(1, 10),
            'purchase_price' => $this->faker->numberBetween(10, 50),
            'sales_price' => $this->faker->numberBetween(50, 100),
            'notes' => $this->faker->sentence,
        ];
    }
}
