<?php

namespace App\Repository\Model\Transport;

use App\Events\Order\Customer\Component\OrderCustomerComponentAddedEvent;
use App\Models\Booking\BookingTraveller;
use App\Models\Booking\Component\BookingTransport;
use App\Models\Order\Component\OrderTransport;
use App\Models\Order\OrderCustomer;
use App\Models\Tour\Tour;
use App\Models\Transport\TransportInventoryTour;
use App\Models\Transport\TransportInventoryTourUpgrade;
use App\Repository\Abstracts\BookingComponentRepository;
use App\Repository\Abstracts\ComponentUpgradeRepository;
use App\Repository\Abstracts\InventoryRepository;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Abstracts\OrderComponentRepository;
use App\Repository\Model\Order\Component\OrderTransportRepository;

class TransportInventoryTourRepository extends InventoryTourRepository
{
    private TransportInventoryTour $tourComponent;

    public function __construct(TransportInventoryTour $tourComponent)
    {
        $this->tourComponent = $tourComponent;
    }

    public static function getAvailableAddons(Tour $tour, OrderCustomer $orderCustomer = null): array
    {
        $components = [];
        foreach ($tour->transportInventoryTours as $component) {
            if ($component->tour_component_type !== "Upgrade") {
                if ($component->available_stock <= 0) continue;
                $components[$component->id] = [];
                $components[$component->id]['id'] = $component->id;
                $components[$component->id]['name'] = $component->transportInventory->transport->name;
                $components[$component->id]['transport_type'] = $component->transportInventory->transport->transportType->name;
            }
        }
        if ($orderCustomer != null) {
            // Remove all components the customer already has
            foreach ($orderCustomer->orderTransports as $oComponent) {
                $component = $oComponent->flightInventoryTour;
                unset($components[$component->id]);
            }
        }
        return $components;
    }

    public function grantToCustomer(OrderCustomer $orderCustomer): ?OrderTransportRepository
    {
        $orderComponent = OrderTransport::create([
            'order_customer_id' => $orderCustomer->id,
            'transport_inventory_tour_id' => $this->tourComponent->id,
            'cost' => $this->tourComponent->tour_sales_price,
        ]);
        event(new OrderCustomerComponentAddedEvent($orderComponent));
        return $orderComponent->repository;
    }

    public function getUpgradeParent(): TransportInventoryTour
    {
        $upgrade = TransportInventoryTourUpgrade::where('upgrade_id', '=', $this->tourComponent->id)->first();
        if (!isset($upgrade)) return $this->tourComponent;
        return $upgrade->base;
    }

    public function onUpgradeTree(ComponentUpgradeRepository $upgradeRepository): bool
    {
        $upgrade = $upgradeRepository->get();
        if (!($upgrade instanceof TransportInventoryTourUpgrade)) return false;
        if ($upgrade->base_id == $this->tourComponent->id) return true;
        foreach ($this->tourComponent->parent()->upgrades as $inventoryTourUpgrade) {
            if ($inventoryTourUpgrade->id == $upgrade->id) return true;
        }
        return false;
    }

    public function get(): TransportInventoryTour
    {
        return $this->tourComponent;
    }

    public function update(array $data): TransportInventoryTour
    {
        $this->tourComponent->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->tourComponent->save();
    }

    public function delete(): bool
    {
        return $this->tourComponent->delete();
    }

    public function isDeleted(): bool
    {
        return $this->tourComponent->trashed();
    }

    public function __toString(): string
    {
        $inventory = $this->tourComponent->transportInventory;
        $component = $inventory->transport;
        return $component->name . ' (' . $component->departureAddress->name . ' to ' . $component->arrivalAddress->name . ')' .
            ' (' . $component->transportType->name . ') ' .
            ' (' . f_datetime($inventory->departs_at) . ' to ' . f_datetime($inventory->arrives_at) . ')' .
            ' (' . $inventory->travelClass->name . ')';
    }

    public function grantToTraveller(BookingTraveller $traveller): ?BookingComponentRepository
    {
        $bookingComponent = BookingTransport::create([
            'booking_traveller_id' => $traveller->id,
            'transport_inventory_tour_id' => $this->tourComponent->id,
        ]);
        return $bookingComponent->repository;
    }

    public function getUsedStock(): int
    {
        return $this->tourComponent->inventory->repository->getUsedStock();
    }

    public function getTotalStock(): int
    {
        return $this->tourComponent->inventory->repository->getTotalStock();
    }

    public function getAvailableStock(): int
    {
        return $this->tourComponent->inventory->repository->getAvailableStock();
    }

    public function getOrderComponent(OrderCustomer $orderCustomer): ?OrderComponentRepository
    {
        $component = $orderCustomer->orderTransports()->where('transport_inventory_tour_id', $this->tourComponent->id)->first();
        return $component?->repository;
    }

    public function getBookingComponent(BookingTraveller $traveller): ?BookingComponentRepository
    {
        $component = $traveller->transport()->where('transport_inventory_tour_id', $this->tourComponent->id)->first();
        return $component?->repository;
    }

    public function getComponentString(): string
    {
        return 'transport';
    }

    public function getCost(): float
    {
        return $this->tourComponent->tour_sales_price;
    }

    public function getComponentType(): string
    {
        return $this->tourComponent->tour_component_type;
    }

    public function getAvailableForUpgrade(): array
    {
        $tour = $this->tourComponent->tour;
        $included = [];
        foreach ($tour->transportInventoryTours as $inventoryTour) {
            $included[$inventoryTour->transportInventory->id] = $inventoryTour->transportInventory->id;
        }
        $data = [];
        foreach ($this->tourComponent->transportInventory->transport->transportInventory as $inventory) {
            if (in_array($inventory->id, $included)) continue;
            if ($inventory->departs_at->gte($tour->date_from->setTime(0, 0)) && $inventory->arrives_at->lte($tour->date_to->setTime(23, 59, 59))) {
                $data[$inventory->id] = $inventory;
            }
        }
        return $data;
    }

    public function getUpgradeId(): int
    {
        $upgrade = TransportInventoryTourUpgrade::where('base_id', '=', $this->tourComponent->id)->first();
        if (isset($upgrade)) return 0;
        $upgrade = TransportInventoryTourUpgrade::where('upgrade_id', '=', $this->tourComponent->id)->first();
        return isset($upgrade) ? $upgrade->id : -1;
    }

    public function isBookable(): bool
    {
        return $this->tourComponent->is_bookable;
    }

    public function getInventory(): ?InventoryRepository
    {
        return $this->tourComponent->inventory->repository;
    }
}
