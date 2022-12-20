<?php

namespace App\Repository\Model\Order\Component;

use App\Models\Order\Component\OrderTransport;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Abstracts\OrderComponentRepository;
use App\Repository\Traits\Component\IsTransport;

class OrderTransportRepository extends OrderComponentRepository
{
    use IsTransport;

    private OrderTransport $orderComponent;

    public function __construct(OrderTransport $orderComponent)
    {
        $this->orderComponent = $orderComponent;
    }

    public function update(array $data): OrderTransport
    {
        $this->orderComponent->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->orderComponent->save();
    }

    public function get(): OrderTransport
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
        $data[] = ['start' => $inventory->departs_at, 'activity' => 'Transport Departure',
            'description' => "{$component->name} ({$component->departureAddress->name} to {$component->arrivalAddress->name}) ({$inventory->travelClass})" . (isset($inventory->ticket_number) ? " ($inventory->ticket_number)" : ""),];
        $data[] = ['start' => $inventory->arrives_at, 'activity' => 'Transport Arrival',
            'description' => "{$component->name} ({$component->departureAddress->name} to {$component->arrivalAddress->name}) ({$inventory->travelClass})" . (isset($inventory->ticket_number) ? " ($inventory->ticket_number)" : ""),];
        return $data;
    }
}
