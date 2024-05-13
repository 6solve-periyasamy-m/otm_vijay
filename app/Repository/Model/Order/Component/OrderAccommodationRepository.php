<?php

namespace App\Repository\Model\Order\Component;

use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\Invoice\InvoiceBillable;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Abstracts\OrderComponentRepository;
use App\Repository\Traits\Component\IsAccommodation;
use Illuminate\Database\Eloquent\Model;

class OrderAccommodationRepository extends OrderComponentRepository
{
    use IsAccommodation;

    private OrderAccommodation $orderComponent;

    public function __construct(OrderAccommodation $orderComponent)
    {
        $this->orderComponent = $orderComponent;
    }

    public function update(array $data): Model
    {
        $this->orderComponent->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->orderComponent->save();
    }

    public function get(): Model
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
        $data[] = ['start' => $inventory->check_in, 'activity' => 'Room Check In',
            'description' => "{$component->name} ({$inventory->roomType->name}) ({$inventory->boardType})"];
        $data[] = ['start' => $inventory->check_out, 'activity' => 'Room Check Out',
            'description' => "{$component->name} ({$inventory->roomType->name}) ({$inventory->boardType})"];
        return $data;
    }

    public static function find($id): OrderAccommodation|null
    {
        return OrderAccommodation::find($id);
    }

    public function getInvoiceBillable(): InvoiceBillable
    {
        return InvoiceBillable::make([
            'description' => $this->__toString(),
            'shared_key' => "accommodation_" . $this->orderComponent->tourComponent->id,
            'amount' => $this->orderComponent->tourComponent->tour_component_type === 'Included' ? 0 : $this->orderComponent->cost,
            'is_base' => $this->orderComponent->tourComponent->tour_component_type === 'Included',
        ]);
    }
}
