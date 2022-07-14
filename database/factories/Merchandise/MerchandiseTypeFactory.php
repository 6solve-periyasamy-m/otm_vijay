<?php

namespace Database\Factories\Merchandise;

use App\Models\Merchandise\MerchandiseType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MerchandiseType>
 */
class MerchandiseTypeFactory extends Factory
{
    protected $model = MerchandiseType::class;

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
