<?php

namespace Database\Factories\Voucher;

use App\Models\Voucher\VoucherCode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VoucherCode>
 */
class VoucherCodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(4, true),
            'description' => $this->faker->sentence,
            'code' => $this->faker->word,
            'active' => true,
        ];
    }
}
