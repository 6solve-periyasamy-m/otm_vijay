<?php

namespace App\Repository\Model\Customer;

use App\Models\Customer\Customer;
use App\Repository\Abstracts\ModelRepository;
use Illuminate\Database\Eloquent\Model;

class CustomerRepository extends ModelRepository
{
    private Customer $customer;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function get(): Model
    {
        return $this->customer;
    }

    public function update(array $data): Model
    {
        $this->customer->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->customer->save();
    }

    public function delete(): bool
    {
        return $this->customer->delete();
    }

    public function isDeleted(): bool
    {
        return $this->customer->trashed();
    }

    public function __toString(): string
    {
        return $this->customer->full_name;
    }

    public function forget(): void
    {
        $this->update([
            "id" => 1,
            "email_address" => null,
            "password" => null,
            "email_verified_at" => null,
            "remember_token" => null,
            "login_token" => null,
            "gender" => null,
            "title" => null,
            "first_name" => "Forgotten",
            "middle_names" => null,
            "last_name" => "Customer",
            "date_of_birth" => null,
            "mobile_number" => null,
            "other_phone_number" => null,
            "home_address_id" => 7,
            "billing_address_id" => 8,
            "emergency_contact_name" => null,
            "emergency_contact_relationship" => null,
            "emergency_contact_telephone" => null,
            "passport_first_name" => null,
            "passport_middle_name" => null,
            "passport_last_name" => null,
            "passport_number" => null,
            "passport_issue_date" => null,
            "passport_expiry_date" => null,
            "passport_country_of_issue" => null,
            "loyalty_number" => null,
            "profile_picture" => null,
            "t_shirt_size_id" => null,
            "hat_size_id" => null,
            "internal_notes" => null,
            "stripe_id" => null,
            "pm_type" => null,
            "pm_last_four" => null,
            "trial_ends_at" => null,
            "external_notes" => null,
            "dietary_notes" => null,
            "mobility_notes" => null,
            "organization_id" => null,
        ]);
        $this->customer->homeAddress->repository->forget();
        $this->customer->billingAddress->repository->forget();
    }
}
