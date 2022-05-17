<?php

namespace Database\Factories\Activity;

use App\Models\Activity\Activity;
use App\Models\Location\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Activity::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $address = Address::factory()->create();
        return [
            'address_id' => $address->id,
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence,
        ];
    }
}
