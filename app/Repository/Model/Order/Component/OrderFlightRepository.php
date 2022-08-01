<?php

namespace App\Repository\Model\Order\Component;

use App\Models\Order\Component\OrderFlight;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Abstracts\OrderComponentRepository;
use App\Repository\Traits\Component\IsFlight;

class OrderFlightRepository extends OrderComponentRepository
{
    use IsFlight;

    private OrderFlight $orderComponent;

    public function __construct(OrderFlight $orderComponent)
    {
        $this->orderComponent = $orderComponent;
    }

    public function update(array $data): OrderFlight
    {
        $this->orderComponent->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->orderComponent->save();
    }

    public function get(): OrderFlight
    {
        return $this->orderComponent;
    }

    public function delete(): bool
    {
        return $this->orderComponent->delete();
    }

    public function isDeleted(): bool
    {
        return $this->orderComponent->trashed();
    }

    public function __toString(): string
    {
        return "{$this->orderComponent->tourComponent}";
    }

    public function getTourComponentType(): string
    {
        return $this->orderComponent->tourComponent->tour_component_type;
    }

    public function getCost(): float
    {
        return $this->orderComponent->cost;
    }

    public function getTourComponent(): ?InventoryTourRepository
    {
        return $this->orderComponent->tourComponent->repository;
    }

    public function getItineraryItems(): array
    {
        $tourComponent = $this->orderComponent->tourComponent;
        $inventory = $tourComponent->inventory;
        $component = $inventory->component;
        $data = [];
        $data[] = ['start' => $inventory->check_in, 'activity' => 'Flight Check In',
            'description' => "{$component->departureAirport->name} to {$component->arrivalAirport->name} (Check In) ({$inventory->flight_number}) ({$inventory->travelClass})"];
        $data[] = ['start' => $inventory->departs_at, 'activity' => 'Flight Departure',
            'description' => "{$component->departureAirport->name} to {$component->arrivalAirport->name} (Departure) ({$inventory->flight_number}) ({$inventory->travelClass})"];
        $data[] = ['start' => $inventory->arrives_at, 'activity' => 'Flight Arrival',
            'description' => "{$component->departureAirport->name} to {$component->arrivalAirport->name} (Arrival) ({$inventory->flight_number}) ({$inventory->travelClass})"];
        return $data;
    }
}
