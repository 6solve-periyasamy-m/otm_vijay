<?php

namespace Database\Factories\Transport;

use App\Models\Transport\TransportInventory;
use Illuminate\Database\Eloquent\Factories\Factory;
use function now;

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
            'departs_at' => now(),
            'departure_time_confirmed' => true,
            'arrives_at' => now()->addHours(5),
            'arrival_time_confirmed' => false,
            'fit_selectable' => true,
            'stock' => $this->faker->numberBetween(1, 10),
            'purchase_price' => $this->faker->numberBetween(10, 50),
            'sales_price' => $this->faker->numberBetween(50, 100),
            'notes' => $this->faker->sentence,
        ];
    }
}
