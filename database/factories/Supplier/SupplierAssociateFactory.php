<?php

namespace Database\Factories\Supplier;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier\SupplierAssociate>
 */
class SupplierAssociateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name,
            'email' => fake()->companyEmail,
            'primary_phone' => fake()->phoneNumber,
            'alternative_phone' => fake()->phoneNumber,
            'job_title' => fake()->jobTitle,
            'notes' => fake()->sentences(5, true),
        ];
    }
}
