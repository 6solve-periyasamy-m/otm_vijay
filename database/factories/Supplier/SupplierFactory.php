<?php

namespace Database\Factories\Supplier;

use App\Models\Location\Address;
use App\Models\Supplier\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->company,
            'email' => fake()->companyEmail,
            'address_id' => Address::factory()->create()->id,
            'telephone' => fake()->phoneNumber,
            'website' => fake()->url,
        ];
    }
}
