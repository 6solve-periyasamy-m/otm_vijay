<?php

namespace Database\Factories\Transport;

use App\Models\Transport\TransportType;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransportTypeFactory extends Factory
{
    protected $model = TransportType::class;

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
