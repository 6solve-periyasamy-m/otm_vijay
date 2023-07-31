<?php

namespace App\Repository\Model\Customer;

use App\Models\Customer\Customer;
use App\Models\Order\Order;
use App\Repository\Abstracts\ModelRepository;

class CustomerRepository extends ModelRepository
{
    private Customer $customer;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function get(): Customer
    {
        return $this->customer;
    }

    public function update(array $data): Customer
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

    public function getDefaultOrder(bool $forceActive = true): Order|null
    {
        $order = $this->customer->orders()->where('cancelled', '=', 0)->orderByDesc('ordered_on')->first();
        if (!isset($order) && !$forceActive) {
            $order = $this->customer->orders()->orderByDesc('ordered_on')->first();
        }
        return $order;
    }

    public function getEditableCustomers(): array
    {
        $customers = [];
        foreach ($this->customer->leadingOrders as $order) {
            foreach ($order->orderCustomers as $oOrderCustomer) {
                $found = $oOrderCustomer->customer;
                if (!isset($found->email_address) || !isset($found->password)) {
                    $customers[$found->id] = $found;
                }
            }
        }
        return $customers;
    }

    public function canEditCustomer(Customer $customer): bool
    {
        if ($this->customer->id === $customer->id) return true;
        if (!isset($edited->email_address) || !isset($edited->password)) {
            foreach ($this->customer->leadingOrders as $order) {
                if ($order->repository->getOrderCustomer($customer) !== null) {
                    return true;
                }
            }
        }
        return false;
    }

    public function isPassportLocked(): bool
    {
        foreach ($this->customer->orders()->with('tour')->where('cancelled', '=', 0)->get() as $order) {
            if ($order->tour->repository->isPassportLocked()) return true;
        }
        return false;
    }

    public function forget(): void
    {
        $this->update([
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
