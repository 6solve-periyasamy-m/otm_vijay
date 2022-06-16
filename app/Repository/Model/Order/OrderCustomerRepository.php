<?php

namespace App\Repository\Model\Order;

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

    public function getAvailableToAdd(): array
    {
        $order = $this->orderCustomer->order;
        $owned = $this->orderCustomer->repository->getOwnedIds();
        $data = [];
        foreach ($order->tour->merchandise as $tourComponent) {
            $owns = in_array($tourComponent->id, $owned['extras']);
            if (!$tourComponent->is_bookable) continue;
            if ($tourComponent->available_stock <= 0 && !$owned) continue;
            $data[] = ['id' => $tourComponent->id, 'name' => $tourComponent->name, 'component' => 'extra', 'type' => $tourComponent->tour_component_type,
                'cost' => $tourComponent->tour_sales_price, 'date' => now()->unix(), 'owned' => $owns,];
        }
        foreach ($order->tour->accommodationInventoryTours as $tourComponent) {
            if (!$tourComponent->is_bookable) continue;
            if ($tourComponent->tour_component_type == 'Add-on') {
                $inventory = $tourComponent->inventory;
                $owns = in_array($tourComponent->id, $owned['accommodation']);
                if ($tourComponent->available_stock <= 0 && !$owned) continue;
                $data[] = ['id' => $tourComponent->id, 'name' => $inventory->__toString(), 'component' => 'accommodation', 'type' => $tourComponent->tour_component_type,
                    'cost' => $tourComponent->tour_sales_price, 'date' => $inventory->check_in->unix(), 'owned' => $owns];
            }
        }
        foreach ($order->tour->activityInventoryTours as $tourComponent) {
            if (!$tourComponent->is_bookable) continue;
            if ($tourComponent->tour_component_type !== 'Upgrade') {
                $inventory = $tourComponent->inventory;
                if (in_array($tourComponent->id, $owned['activities'])) continue; // Owned components will be shown elsewhere
                if ($tourComponent->available_stock <= 0) continue;
                $data[] = ['id' => $tourComponent->id, 'name' => $inventory->__toString(), 'component' => 'activity', 'type' => $tourComponent->tour_component_type,
                    'cost' => $tourComponent->tour_sales_price, 'date' => $inventory->starts_at->unix(), 'owned' => false,];
            }
        }
        foreach ($order->tour->flightInventoryTours as $tourComponent) {
            if (!$tourComponent->is_bookable) continue;
            if ($tourComponent->tour_component_type !== 'Upgrade') {
                $inventory = $tourComponent->inventory;
                if (in_array($tourComponent->id, $owned['flights'])) continue; // Owned components will be shown elsewhere
                if ($tourComponent->available_stock <= 0) continue;
                $data[] = ['id' => $tourComponent->id, 'name' => $inventory->__toString(), 'component' => 'flight', 'type' => $tourComponent->tour_component_type,
                    'cost' => $tourComponent->tour_sales_price, 'date' => $inventory->check_in->unix(), 'owned' => false,];
            }
        }
        foreach ($order->tour->transportInventoryTours as $tourComponent) {
            if (!$tourComponent->is_bookable) continue;
            if ($tourComponent->tour_component_type !== 'Upgrade') {
                $inventory = $tourComponent->inventory;
                if (in_array($tourComponent->id, $owned['transport'])) continue; // Owned components will be shown elsewhere
                if ($tourComponent->available_stock <= 0) continue;
                $data[] = ['id' => $tourComponent->id, 'name' => $inventory->__toString(), 'component' => 'transport', 'type' => $tourComponent->tour_component_type,
                    'cost' => $tourComponent->tour_sales_price, 'date' => $inventory->departs_at->unix(), 'owned' => false,];
            }
        }
        return $data;
    }

    public function getOwnedIds(): array
    {
        $data = [];
        $subData = [];
        foreach ($this->orderCustomer->orderAccommodation as $orderComponent) {
            $subData[] = $orderComponent->tourComponent->id;
            if ($orderComponent->tourComponent->tour_component_type == 'Upgrade') {
                $subData[] = $orderComponent->tourComponent->parent()->id;
            }
        }
        $data['accommodation'] = array_unique($subData);
        $subData = [];
        foreach ($this->orderCustomer->orderActivities as $orderComponent) {
            $subData[] = $orderComponent->tourComponent->id;
            if ($orderComponent->tourComponent->tour_component_type == 'Upgrade') {
                $subData[] = $orderComponent->tourComponent->parent()->id;
            }
        }
        $data['activities'] = array_unique($subData);
        $subData = [];
        foreach ($this->orderCustomer->orderFlights as $orderComponent) {
            $subData[] = $orderComponent->tourComponent->id;
            if ($orderComponent->tourComponent->tour_component_type == 'Upgrade') {
                $subData[] = $orderComponent->tourComponent->parent()->id;
            }
        }
        $data['flights'] = array_unique($subData);
        $subData = [];
        foreach ($this->orderCustomer->orderTransports as $orderComponent) {
            $subData[] = $orderComponent->tourComponent->id;
            if ($orderComponent->tourComponent->tour_component_type == 'Upgrade') {
                $subData[] = $orderComponent->tourComponent->parent()->id;
            }
        }
        $data['transport'] = array_unique($subData);
        $subData = [];
        foreach ($this->orderCustomer->orderMerchandise as $orderComponent) {
            $subData[] = $orderComponent->tourComponent->id;
        }
        $data['extras'] = array_unique($subData);
        return $data;
    }

    public function __toString(): string
    {
        return "{$this->orderCustomer->customer_name} ({$this->orderCustomer->order->booking_reference})";
    }

    public function addAllIncluded()
    {
        foreach ($this->orderCustomer->order->tour->repository->getComponents(false, true, true, true, true, ['Included',]) as $inventoryTourRepository) {
            if (!$inventoryTourRepository->isBookable()) continue;
            $inventoryTourRepository->grantToCustomer($this->orderCustomer);
        }
    }

    /**
     * @return OrderComponentRepository[]
     */
    public function getComponents(bool $accommodation = true, bool $activities = true, bool $flights = true, bool $transport = true, bool $extras = true, array $typeFilters = ['Included', 'Upgrade', 'Add-on']): array
    {
        $components = [];
        if ($accommodation) {
            foreach ($this->orderCustomer->orderAccommodation as $orderComponent) {
                if (!in_array($orderComponent->tourComponent->tour_component_type, $typeFilters)) continue;
                $components[] = $orderComponent->repository;
            }
        }
        if ($activities) {
            foreach ($this->orderCustomer->orderActivities as $orderComponent) {
                if (!in_array($orderComponent->tourComponent->tour_component_type, $typeFilters)) continue;
                $components[] = $orderComponent->repository;
            }
        }
        if ($flights) {
            foreach ($this->orderCustomer->orderFlights as $orderComponent) {
                if (!in_array($orderComponent->tourComponent->tour_component_type, $typeFilters)) continue;
                $components[] = $orderComponent->repository;
            }
        }
        if ($transport) {
            foreach ($this->orderCustomer->orderTransports as $orderComponent) {
                if (!in_array($orderComponent->tourComponent->tour_component_type, $typeFilters)) continue;
                $components[] = $orderComponent->repository;
            }
        }
        if ($extras){
            foreach ($this->orderCustomer->orderMerchandise as $orderComponent) {
                if (!in_array($orderComponent->tourComponent->tour_component_type, $typeFilters)) continue;
                $components[] = $orderComponent->repository;
            }
        }
        return $components;
    }

    public function getItinerary(): array
    {
        $data = [];
        foreach ($this->getComponents(true, true, true, true, false) as $componentRepository) {
            $data = array_merge($data, $componentRepository->getItineraryItems());
        }
        usort($data, function ($a, $b) { return $a['start']->unix() <=> $b['start']->unix(); });
        return $data;
    }

    /**
     * Get the addons and upgrades for a specific customer
     * @return array{addons:array,upgrades:array,additionalValue:float} The list of upgrades, addons and the sum of their costs
     */
    public function getAdditionalCosts(): array
    {
        $upgrades = [];
        $addons = [];
        $additionalValue = 0;
        // Accommodation Additionals are going to be calculated per group (A:Celeste Gateley)
        foreach ($this->orderCustomer->repository->getComponents(false, true, true, true, true, ['Add-on', 'Upgrade']) as $componentRepository) {
            if ($componentRepository->getTourComponentType() == 'Add-on') {
                $addons[] = ['addon' => $componentRepository->get(), 'description' => "{$componentRepository->getTourComponent()}  ({$this->orderCustomer->customer_name})",];
            }
            if ($componentRepository->getTourComponentType() == 'Upgrade') {
                $addons[] = ['upgrade' => $componentRepository->get(), 'description' => "{$componentRepository->getTourComponent()}  ({$this->orderCustomer->customer_name})",];
            }
            $additionalValue += $componentRepository->getCost();
        }
        return ['addons' => $addons, 'upgrades' => $upgrades, 'additionalValue' => $additionalValue,];
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
}
