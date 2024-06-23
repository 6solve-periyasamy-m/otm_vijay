<?php

namespace App\Repository\Abstracts;

use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\Component\OrderActivity;
use App\Models\Order\Component\OrderFlight;
use App\Models\Order\Component\OrderMerchandise;
use App\Models\Order\Component\OrderTransport;
use App\Models\Order\Invoice\InvoiceBillable;
use App\Models\Order\Order;
use App\Repository\Interfaces\HasComponentType;
use App\Repository\Storage\Customer\Component\OrderComponent;
use App\Repository\Storage\Itinerary\ItineraryItem;

abstract class OrderComponentRepository extends ModelRepository implements HasComponentType
{
    abstract public function getTourComponentType(): string;
    abstract public function getCost(): float;
    abstract public function getTourComponent(): ?InventoryTourRepository;
    abstract public function getItineraryItems(): array;
    abstract public function getInvoiceBillable(): InvoiceBillable;
    abstract public function getQuantity(Order $order = null): int;
    abstract public function getItineraryItem(Order $order = null): ItineraryItem;

    public function getAbstractOrderComponent(): OrderComponent
    {
        return new OrderComponent($this->getTourComponent());
    }

    public static function getComponent(string $type, int $id): ?OrderComponentRepository
    {
        return match ($type) {
            'accommodation' => OrderAccommodation::find($id)?->repository,
            'activity' => OrderActivity::find($id)?->repository,
            'flight' => OrderFlight::find($id)?->repository,
            'transport' => OrderTransport::find($id)?->repository,
            'merchandise' => OrderMerchandise::find($id)?->repository,
            default => null,
        };
    }
}
