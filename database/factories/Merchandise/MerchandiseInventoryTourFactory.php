<?php

namespace Database\Factories\Merchandise;

use App\Models\Merchandise\MerchandiseInventoryTour;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MerchandiseInventoryTour>
 */
class MerchandiseInventoryTourFactory extends Factory
{
    protected $model = MerchandiseInventoryTour::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'tour_component_type' => 'Included',
            'tour_sales_price' => 50,
        ];
    }
}
