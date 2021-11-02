<?php

namespace Database\Factories;

use App\Models\Accommodation;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccommodationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Accommodation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'region_id' => $this->faker->numberBetween(1, 8),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence,
            'audit_date' => now(),
            'address' => $this->faker->address,
        ];
    }
}
