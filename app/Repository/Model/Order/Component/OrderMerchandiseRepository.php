<?php

namespace App\Repository\Model\Order\Component;

use App\Models\Order\Component\OrderMerchandise;
use App\Models\Order\Invoice\InvoiceBillable;
use App\Models\Order\Order;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Abstracts\OrderComponentRepository;
use App\Repository\Storage\Itinerary\ItineraryItem;
use App\Repository\Traits\Component\IsMerchandise;
use Illuminate\Database\Eloquent\Model;

class OrderMerchandiseRepository extends OrderComponentRepository
{
    use IsMerchandise;

    private OrderMerchandise $orderComponent;

    public function __construct(OrderMerchandise $orderComponent)
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
        return [];
    }

    public static function find($id): OrderMerchandise|null
    {
        return OrderMerchandise::find($id);
    }

    public function getInvoiceBillable(): InvoiceBillable
    {
        return InvoiceBillable::make([
            'description' => $this->__toString(),
            'shared_key' => "merchandise_" . $this->orderComponent->tourComponent->id,
            'amount' => $this->orderComponent->tourComponent->tour_component_type === 'Included' ? 0 : $this->orderComponent->cost,
            'is_base' => $this->orderComponent->tourComponent->tour_component_type === 'Included',
        ]);
    }

    public function getQuantity(Order $order = null): int
    {
        $order = $order ?? $this->orderComponent->orderCustomer->order;
        return $order?->orderMerchandise()->where('merchandise_inventory_tour_id', '=', $this->orderComponent->merchandise_inventory_tour_id)->count() ?? 0;
    }

    public function getItineraryItem(Order $order = null): ItineraryItem
    {
        return $this->getTourComponent()?->getItineraryItem($this->getQuantity($order));
    }
}
