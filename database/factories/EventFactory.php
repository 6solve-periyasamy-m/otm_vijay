<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'event_title' => $this->faker->sentence,
            'booking_url' => $this->faker->url,
            'event_start_date' => $this->faker->date,
            'event_end_date' => $this->faker->date
        ];
    }
}
