<?php

namespace Database\Factories\Accommodation;

use App\Models\Accommodation\BoardType;
use Illuminate\Database\Eloquent\Factories\Factory;

class BoardTypeFactory extends Factory
{

    protected $model = BoardType::class;

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
