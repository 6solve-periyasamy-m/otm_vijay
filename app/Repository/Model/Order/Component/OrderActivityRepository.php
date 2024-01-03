<?php

namespace App\Repository\Model\Order\Component;

use App\Models\Order\Component\OrderActivity;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Abstracts\OrderComponentRepository;
use App\Repository\Traits\Component\IsActivity;

class OrderActivityRepository extends OrderComponentRepository
{
    use IsActivity;

    private OrderActivity $orderComponent;

    public function __construct(OrderActivity $orderComponent)
    {
        $this->orderComponent = $orderComponent;
    }

    public function update(array $data): OrderActivity
    {
        $this->orderComponent->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->orderComponent->save();
    }

    public function get(): OrderActivity
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
        return [[
            'start' => $inventory->starts_at,
            'activity' => $component->activityType,
            'description' => "{$component->name} ({$component->address}) ({$inventory->ticketType})"],
            ];
    }

    public static function find($id): OrderActivity|null
    {
        return OrderActivity::find($id);
    }
}
