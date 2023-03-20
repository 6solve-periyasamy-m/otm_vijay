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
}
