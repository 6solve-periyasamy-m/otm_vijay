<?php

namespace App\Repository\Model\Order\Component;

use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\Invoice\InvoiceBillable;
use App\Models\Order\Order;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Abstracts\OrderComponentRepository;
use App\Repository\Storage\Itinerary\ItineraryItem;
use App\Repository\Traits\Component\IsAccommodation;
use Carbon\Carbon;
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
        return (string)($this->orderComponent->tourComponent);
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
        return new InvoiceBillable([
            'description' => $this->__toString(),
            'shared_key' => "accommodation_" . $this->orderComponent->tourComponent->id,
            'amount' => $this->orderComponent->tourComponent->tour_component_type === 'Included' ? 0 : $this->orderComponent->cost,
            'is_base' => $this->orderComponent->tourComponent->tour_component_type === 'Included',
        ]);
    }

    public function getQuantity(Order $order = null): int
    {
        $order = $order ?? $this->orderComponent->group->orderCustomers()->first()?->order;
        if ($order === null) { return 0; }
        $quantity = 0;
        foreach ($order->groups as $group) {
            $quantity += $group->rooms()->where('accommodation_inventory_tour_id', '=', $this->orderComponent->accommodation_inventory_tour_id)->count();
        }
        return $quantity;
    }
    public function getOrderComponent()
    {
        return $this->orderComponent;
    }

    public function getItineraryItem(Order $order = null): ItineraryItem
    {
        return $this->getTourComponent()?->getItineraryItem($this->getQuantity($order)); // @phpstan-ignore
    }

    public function getStartTime(): Carbon
    {
        return $this->orderComponent->tourComponent->inventory->check_in;
    }

    public function getEndTime(): Carbon
    {
        return $this->orderComponent->tourComponent->inventory->check_out;
    }

    public function getCostToCompany(): float
    {
        return $this->orderComponent->purchase_price ?? $this->orderComponent->tourComponent?->inventory?->local_purchase_price ?? 0.0;
    }
}
