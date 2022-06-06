<?php

namespace App\Repository\Model\Flight;

use App\Events\Order\Customer\Component\OrderCustomerComponentAddedEvent;
use App\Models\Booking\BookingTraveller;
use App\Models\Booking\Component\BookingFlight;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Flight\FlightInventoryTourUpgrade;
use App\Models\Order\Component\OrderFlight;
use App\Models\Order\OrderCustomer;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\BookingComponentRepository;
use App\Repository\Abstracts\ComponentUpgradeRepository;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Model\Order\Component\OrderFlightRepository;

class FlightInventoryTourRepository extends InventoryTourRepository
{
    private FlightInventoryTour $tourComponent;

    public function __construct(FlightInventoryTour $tourComponent)
    {
        $this->tourComponent = $tourComponent;
    }

    public static function getAvailableAddons(Tour $tour, OrderCustomer $orderCustomer = null): array
    {
        $components = [];
        foreach ($tour->flightInventoryTours as $component) {
            if ($component->tour_component_type !== "Upgrade") {
                if ($component->available_stock <= 0) continue;
                $components[$component->id] = [];
                $components[$component->id]['id'] = $component->id;
                $components[$component->id]['name'] = $component->flightInventory->flight_number;
                $components[$component->id]['travel_class'] = $component->flightInventory->travelClass->name;
            }
        }
        if ($orderCustomer != null) {
            // Remove all components the customer already has
            foreach ($orderCustomer->orderFlights as $oComponent) {
                $component = $oComponent->flightInventoryTour;
                unset($components[$component->id]);
            }
        }
        return $components;
    }

    /**
     * @param OrderCustomer $orderCustomer
     * @return OrderFlightRepository|null
     */
    public function grantToCustomer(OrderCustomer $orderCustomer): ?OrderFlightRepository
    {
        $orderComponent = OrderFlight::create([
            'order_customer_id' => $orderCustomer->id,
            'flight_inventory_tour_id' => $this->tourComponent->id,
            'cost' => $this->tourComponent->tour_sales_price,
        ]);
        event(new OrderCustomerComponentAddedEvent($orderComponent));
        return $orderComponent->repository;
    }

    public function getUpgradeParent(): FlightInventoryTour
    {
        $upgrade = FlightInventoryTourUpgrade::where('upgrade_id', '=', $this->tourComponent->id)->first();
        if (!isset($upgrade)) return $this->tourComponent;
        return $upgrade->base;
    }

    public function onUpgradeTree(ComponentUpgradeRepository $upgradeRepository): bool
    {
        $upgrade = $upgradeRepository->get();
        if (!($upgrade instanceof FlightInventoryTourUpgrade)) return false;
        if ($upgrade->base_id == $this->tourComponent->id) return true;
        foreach ($this->tourComponent->parent()->upgrades as $inventoryTourUpgrade) {
            if ($inventoryTourUpgrade->id == $upgrade->id) return true;
        }
        return false;
    }

    public function get(): FlightInventoryTour
    {
        return $this->tourComponent;
    }

    public function update(array $data): FlightInventoryTour
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
        $inventory = $this->tourComponent->flightInventory;
        $component = $inventory->flight;
        return $component->airline->name . ' (' . $inventory->flight_number . ') ' . $component->departureAirport->name . ' to ' . $component->arrivalAirport->name .
            ' (' . f_datetime($inventory->departs_at) . ' to ' . f_datetime($inventory->arrives_at) . ')' .
            ' (' . $inventory->travelClass->name . ')';
    }

    public function grantToTraveller(BookingTraveller $traveller): ?BookingComponentRepository
    {
        $bookingComponent = BookingFlight::create([
            'booking_traveller_id' => $traveller->id,
            'flight_inventory_tour_id' => $this->tourComponent->id,
        ]);
        return $bookingComponent->repository;
    }
}