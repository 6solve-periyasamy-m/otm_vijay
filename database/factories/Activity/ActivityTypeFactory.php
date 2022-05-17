<?php

namespace Database\Factories\Activity;

use App\Models\Activity\ActivityType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityTypeFactory extends Factory
{
    protected $model = ActivityType::class;

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
