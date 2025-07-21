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
use Carbon\Carbon;

abstract class OrderComponentRepository extends ModelRepository implements HasComponentType
{
    abstract public function getTourComponentType(): string;
    abstract public function getCost(): float;
    abstract public function getTourComponent(): ?InventoryTourRepository;
    abstract public function getItineraryItems(): array;
    abstract public function getInvoiceBillable(): InvoiceBillable;
    abstract public function getQuantity(Order|null $order = null): int;
    abstract public function getItineraryItem(Order|null $order = null): ItineraryItem;
    abstract public function getStartTime(): Carbon|null;
    abstract public function getEndTime(): Carbon|null;
    abstract public function getCostToCompany(): float;

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
