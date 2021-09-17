<?php

namespace Database\Factories;

use App\Models\FlightInventoryTour;
use Illuminate\Database\Eloquent\Factories\Factory;

class FlightInventoryTourFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FlightInventoryTour::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'tour_id' => 1,
            'flight_inventory_id' => 1,
            'tour_component_type_id' => 1,
            'tour_sales_price' => null,
            'flight_type' => 'Outbound'
        ];
    }
}
