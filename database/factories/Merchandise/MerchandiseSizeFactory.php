<?php

namespace Database\Factories\Merchandise;

use App\Models\Merchandise\MerchandiseSize;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MerchandiseSize>
 */
class MerchandiseSizeFactory extends Factory
{
    protected $model = MerchandiseSize::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
        ];
    }
}
