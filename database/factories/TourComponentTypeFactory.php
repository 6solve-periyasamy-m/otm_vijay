<?php

namespace Database\Factories;

use App\Models\TourComponentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class TourComponentTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TourComponentType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => 1,
            'component_type_name' => 'Included Component',
        ];
    }
}
