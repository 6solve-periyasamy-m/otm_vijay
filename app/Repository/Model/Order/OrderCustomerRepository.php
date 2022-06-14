<?php

namespace App\Repository\Model\Order;

use App\Events\Order\Customer\Component\OrderCustomerComponentAddedEvent;
use App\Models\Customer\Customer;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Repository\Abstracts\ModelRepository;
use App\Repository\Abstracts\OrderComponentRepository;

class OrderCustomerRepository extends ModelRepository
{
    private OrderCustomer $orderCustomer;

    public function __construct(OrderCustomer $orderCustomer)
    {
        $this->orderCustomer = $orderCustomer;
    }

    public static function find(Order $order, Customer $customer): ?OrderCustomer
    {
        return OrderCustomer::where('order_id', $order->id)->where('customer_id', $customer->id)->first();
    }

    /**
     * @return OrderComponentRepository[]
     */
    public function getComponents(bool $includeAccommodation = true, array $typeFilters = ['Included', 'Upgrade', 'Add-on']): array
    {
        $components = [];
        if ($includeAccommodation) {
            foreach ($this->orderCustomer->orderAccommodation() as $orderComponent) {
                if (!in_array($orderComponent->tourComponent->tour_component_type, $typeFilters)) continue;
                $components[] = $orderComponent->repository;
            }
        }
        foreach ($this->orderCustomer->orderActivities as $orderComponent) {
            if (!in_array($orderComponent->tourComponent->tour_component_type, $typeFilters)) continue;
            $components[] = $orderComponent->repository;
        }
        foreach ($this->orderCustomer->orderFlights as $orderComponent) {
            if (!in_array($orderComponent->tourComponent->tour_component_type, $typeFilters)) continue;
            $components[] = $orderComponent->repository;
        }
        foreach ($this->orderCustomer->orderTransports as $orderComponent) {
            if (!in_array($orderComponent->tourComponent->tour_component_type, $typeFilters)) continue;
            $components[] = $orderComponent->repository;
        }
        foreach ($this->orderCustomer->orderMerchandise as $orderComponent) {
            if (!in_array($orderComponent->tourComponent->tour_component_type, $typeFilters)) continue;
            $components[] = $orderComponent->repository;
        }
        return $components;
    }

    public function addAllIncluded()
    {
        foreach ($this->orderCustomer->order->tour->repository->getComponents(false, true, true, true, true, ['Included',]) as $inventoryTourRepository) {
            if (!$inventoryTourRepository->isBookable()) continue;
            $inventoryTourRepository->grantToCustomer($this->orderCustomer);
            event(new OrderCustomerComponentAddedEvent($inventoryTourRepository->get()));
        }
    }

    public function get(): OrderCustomer
    {
        return $this->orderCustomer;
    }

    public function update(array $data): OrderCustomer
    {
        $this->orderCustomer->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->orderCustomer->save();
    }

    public function delete(): bool
    {
        return $this->orderCustomer->delete();
    }

    public function isDeleted(): bool
    {
        return $this->orderCustomer->trashed();
    }

    public function __toString(): string
    {
        return "{$this->orderCustomer->customer_name} ({$this->orderCustomer->order->booking_reference})";
    }
}
