<?php

namespace Database\Factories;

use App\Models\FlightInventory;
use Illuminate\Database\Eloquent\Factories\Factory;

class FlightInventoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FlightInventory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => 1,
            'check_in' => '2022-05-14 12:30:00',
            'departs_at' => '2022-05-14 14:30:00',
            'arrives_at' => '2022-05-14 16:30:00',
            'flight_number' => $this->faker->word,
            'travel_class_id' => 1,
            'fit_selectable' => 1,
            'stock' => 5,
            'purchase_price' => 200,
            'sales_price' => 300,
            'currency' => 'GBP',
            'notes' => $this->faker->sentence
        ];
    }
}
