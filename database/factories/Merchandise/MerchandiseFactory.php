<?php

namespace Database\Factories\Merchandise;

use App\Models\Merchandise\Merchandise;
use App\Models\Merchandise\MerchandiseType;
use Illuminate\Database\Eloquent\Factories\Factory;

class MerchandiseFactory extends Factory
{
    protected $model = Merchandise::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = MerchandiseType::first() ?? MerchandiseType::factory()->create();
        return [
            'name' => implode(' ', $this->faker->words),
            'merchandise_type_id' => $type->id,
        ];
    }
}
