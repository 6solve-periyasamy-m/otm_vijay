<?php

namespace Database\Factories\Accommodation;

use App\Models\Accommodation\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomTypeFactory extends Factory
{

    protected $model = RoomType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'maximum_occupancy' => 1,
        ];
    }
}
