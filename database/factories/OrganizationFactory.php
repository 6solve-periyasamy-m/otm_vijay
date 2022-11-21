<?php

namespace Database\Factories;

use App\Models\Customer\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Organization>
 */
class OrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'contact_number' => $this->faker->phoneNumber,
            'contact_email' => $this->faker->companyEmail,
        ];
    }
}
