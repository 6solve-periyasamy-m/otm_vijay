<?php

namespace Database\Factories\Customer;

use App\Models\Customer\Customer;
use App\Models\Helper\AddressParent;
use App\Models\Location\Address;
use Illuminate\Database\Eloquent\Factories\Factory;
use function now;

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
        $homeAddress = Address::create([
            'name' => 'Pregenerated Customer Name',
            'parent' => AddressParent::CUSTOMER,
            'address_line_1' => $this->faker->streetAddress,
            'town' => $this->faker->city,
            'region' => $this->faker->state,
            'country_id' => 1,
            'postcode' => $this->faker->postcode
        ]);
        $billingAddress = $homeAddress->repository->cloneToNew(AddressParent::CUSTOMER);
        return [
            'title' => $this->faker->title,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'date_of_birth' => $this->faker->date,
            'mobile_number' => $this->faker->phoneNumber,
            'email_address' => $this->faker->email,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'gender' => ['male', 'female', 'other'][array_rand(['male', 'female', 'other'])],
            'home_address_id' => $homeAddress->id,
            'billing_address_id' => $billingAddress->id,
            'emergency_contact_name' => $this->faker->firstName . ' ' . $this->faker->lastName,
            'emergency_contact_relationship' => 'Partner',
            'emergency_contact_telephone' => $this->faker->phoneNumber,
            'passport_first_name' => $this->faker->firstName,
            'passport_last_name' => $this->faker->lastName,
            'passport_number' => $this->faker->numberBetween(1000000, 9999999),
            'passport_issue_date' => now(),
            'passport_expiry_date' => $this->faker->dateTimeBetween('-6 months', '+6 months'),
            'internal_notes' => $this->faker->sentence,
            //'external_notes' => $this->faker->sentence,
            //'dietary_notes' => $this->faker->sentence,
            //'mobility_notes' => $this->faker->sentence,
            'loyalty_number' => $this->faker->randomNumber(9),
        ];
    }
}
