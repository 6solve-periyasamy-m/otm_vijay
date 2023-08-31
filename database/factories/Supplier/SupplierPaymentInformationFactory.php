<?php

namespace Database\Factories\Supplier;

use App\Models\Supplier\SupplierPaymentInformation;
use App\Models\System\Bank;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SupplierPaymentInformation>
 */
class SupplierPaymentInformationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $bank = Bank::first() ?? Bank::factory()->create();
        return [
            'bank_id' => $bank->id,
            'account_number' => fake()->bankAccountNumber,
            'sort_code' => fake()->randomNumber(2, true) . '-' .fake()->randomNumber(2, true) . '-' .fake()->randomNumber(2, true),
            'bic_swift_code' => fake()->swiftBicNumber,
            'iban' => fake()->iban(),
        ];
    }
}
