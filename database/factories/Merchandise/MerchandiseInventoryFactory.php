<?php

namespace Database\Factories\Merchandise;

use App\Models\Merchandise\MerchandiseInventory;
use App\Models\Merchandise\Variant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MerchandiseInventory>
 */
class MerchandiseInventoryFactory extends Factory
{
    protected $model = MerchandiseInventory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $variant = Variant::first() ?? Variant::factory()->create();
        return [
            'variant_id' => $variant->id,
            'fit_selectable' => true,
            'stock' => 50,
            'purchase_price' => 25,
            'sales_price' => 50,
            'notes' => $this->faker->sentence
        ];
    }
}
