<?php

namespace Database\Factories;

use App\Models\TravelClass;
use Illuminate\Database\Eloquent\Factories\Factory;

class TravelClassFactory extends Factory
{
    protected $model = TravelClass::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
        ];
    }
}
