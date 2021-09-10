<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->title,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'date_of_birth' => $this->faker->date,
            'mobile_number' => $this->faker->phoneNumber,
            'email_address' => $this->faker->email,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'gender' => ['male', 'female', 'other'][array_rand(['male', 'female', 'other'])],
            'address_line_1' => $this->faker->streetAddress,
            'address_line_2' => $this->faker->secondaryAddress,
            'address_line_3' => $this->faker->secondaryAddress,
            'town' => $this->faker->city,
            'country' => $this->faker->country,
            'postcode' => $this->faker->postcode,
            'same_address' => false,
            'billing_line_1' => $this->faker->streetAddress,
            'billing_line_2' => $this->faker->secondaryAddress,
            'billing_line_3' => $this->faker->secondaryAddress,
            'billing_town' => $this->faker->city,
            'billing_country' => $this->faker->country,
            'billing_postcode' => $this->faker->postcode,
            'emergency_contact_name' => $this->faker->firstName . ' ' . $this->faker->lastName,
            'emergency_contact_relationship' => 'Partner',
            'emergency_contact_telephone' => $this->faker->phoneNumber,
            'passport_first_name' => $this->faker->firstName,
            'passport_last_name' => $this->faker->lastName,
            'passport_number' => $this->faker->numberBetween(1000000, 9999999),
            'passport_issue_date' => now(),
            'passport_expiry_date' => now(),
            'notes' => $this->faker->sentence,
            'loyalty_number' => $this->faker->randomNumber(9),
        ];
    }
}
