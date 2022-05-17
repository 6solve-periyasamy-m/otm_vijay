<?php

namespace Database\Factories\Accommodation;

use App\Models\Accommodation\Accommodation;
use App\Models\Location\Address;
use Illuminate\Database\Eloquent\Factories\Factory;
use function now;

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
        $address = Address::factory()->create();
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence,
            'audit_date' => now(),
            'address_id' => $address->id,
        ];
    }
}
