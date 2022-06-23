<?php

namespace Database\Factories\Quote;

use App\Models\Location\Address;
use App\Models\Location\AddressParent;
use App\Models\Quote\QuoteProspect;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuoteProspect>
 */
class QuoteProspectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $homeAddress = Address::create([
            'name' => 'Pregenerated Prospect Name',
            'address_parent_id' => AddressParent::getParentId('customer'),
            'address_line_1' => $this->faker->streetAddress,
            'town' => $this->faker->city,
            'region' => $this->faker->state,
            'country_id' => 1,
            'postcode' => $this->faker->postcode
        ]);
        $billingAddress = $homeAddress->repository->cloneToNew(AddressParent::getParentId('customer'));
        return [
            'title' => $this->faker->title,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'date_of_birth' => $this->faker->date,
            'mobile_number' => $this->faker->phoneNumber,
            'email_address' => $this->faker->email,
            'home_address_id' => $homeAddress->id,
            'billing_address_id' => $billingAddress->id,
        ];
    }
}
