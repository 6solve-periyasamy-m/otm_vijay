<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'payment_method_id' => $this->faker->numberBetween(1, 3),
            'amount' => $this->faker->numberBetween(100, 200),
            'payment_type' => 'Installment',
            'paid_on' => now(),
        ];
    }
}
