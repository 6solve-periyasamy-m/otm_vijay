<?php

namespace App\Repository\Model\Customer;

use App\Models\Customer\Customer;
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
        if (!isset($edited->email_address) || !isset($edited->password)) {
            foreach ($this->customer->leadingOrders as $order) {
                if ($order->repository->getOrderCustomer($customer) !== null) {
                    return true;
                }
            }
        }
        return false;
    }
}
