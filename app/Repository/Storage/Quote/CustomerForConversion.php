<?php

namespace App\Repository\Storage\Quote;

use App\Models\Customer\Customer;
use App\Models\Order\OrderCustomer;
use App\Models\Quote\Quote;
use App\Repository\Storage\ConvertedCustomer;
use JetBrains\PhpStorm\ArrayShape;
use Livewire\Wireable;

class CustomerForConversion implements Wireable
{
    private array $components = [];
    private Customer|null $customerModel;
    public int|string|null $customer;
    public OrderCustomer|null $orderCustomer;

    public function __construct(public readonly bool $paying, public readonly bool $travelling) {
        $this->customer = null;
    }

    #[ArrayShape(['customer' => "int|null|string", 'paying' => "bool", 'travelling' => "bool", 'components' => "array"])]
    public function toLivewire(): array
    {
        return [
            'customer' => $this->customer,
            'paying' => $this->paying,
            'travelling' => $this->travelling,
            'components' => $this->components,
        ];
    }

    public function getConvertedCustomer(Quote $quote): ConvertedCustomer
    {
        $customer = $this->getCustomer() ?? $quote->repository->generateGenericCustomer($this->paying);
        return new ConvertedCustomer($customer, $this->paying, $this->travelling);
    }

    public function setCustomer(string|int|null $customer): static
    {
        $this->customer = $customer;
        $this->customerModel = Customer::find($customer);
        return $this;
    }

    public function getCustomer(): Customer|null
    {
        $this->customerModel = $this->customerModel ?? Customer::find($this->customer);
        return $this->customerModel;
    }

    public function setComponents(array $components): static
    {
        $this->components = $components;
        return $this;
    }

    private function findComponentKey(string $type, int $id): int|null
    {
        foreach ($this->components as $key => $component) {
            if ($component['type'] === $type && $component['id'] === $id) {
                return $key;
            }
        }
        return null;
    }

    public function hasComponent(string $type, int $id): bool
    {
        return $this->findComponentKey($type, $id) !== null;
    }

    public function addComponent(string $type, int $id): static
    {
        $this->components[] = ['type' => $type, 'id' => $id];
        return $this;
    }

    public function removeComponent(string $type, int $id): static
    {
        $key = $this->findComponentKey($type, $id);
        if ($key !== null) {
            unset($this->components[$key]);
        }
        return $this;
    }

    public static function fromLivewire($value): static
    {
        return (new static($value['paying'], $value['travelling']))->setCustomer($value['customer'])->setComponents($value['components']);
    }
}