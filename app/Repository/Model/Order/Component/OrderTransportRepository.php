<?php

namespace App\Repository\Model\Order\Component;

use App\Models\Order\Component\OrderTransport;
use App\Models\Order\Invoice\InvoiceBillable;
use App\Models\Order\Order;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Abstracts\OrderComponentRepository;
use App\Repository\Storage\Itinerary\ItineraryItem;
use App\Repository\Traits\Component\IsTransport;
use Carbon\Carbon;

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
        $tourComponent = $this->orderComponent->tourComponent;
        $inventory = $tourComponent->transportInventory;
        $component = $inventory->transport;
        return $component->name . ' (' . $component->departureAddress->name . ' to ' . $component->arrivalAddress->name . ')' .
            ' (' . $component->transportType->name . ') ' .
            ' (' . f_datetime($this->getStartTime()) . ' to ' . f_datetime($this->getEndTime()) . ')' .
            ' (' . $inventory->travelClass->name . ')';
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
        $data[] = ['start' => $this->orderComponent->repository->getStartTime(), 'activity' => 'Transport Departure',
            'description' => "{$component->name} ({$component->departureAddress->name} to {$component->arrivalAddress->name}) ({$inventory->travelClass})" . (isset($inventory->transport_number) ? " ($inventory->transport_number)" : ""),];
        $data[] = ['start' => $inventory->arrives_at, 'activity' => 'Transport Arrival',
            'description' => "{$component->name} ({$component->departureAddress->name} to {$component->arrivalAddress->name}) ({$inventory->travelClass})" . (isset($inventory->transport_number) ? " ($inventory->transport_number)" : ""),];
        return $data;
    }

    public static function find($id): OrderTransport|null
    {
        return OrderTransport::find($id);
    }

    public function getInvoiceBillable(): InvoiceBillable
    {
        return InvoiceBillable::make([
            'description' => $this->__toString(),
            'shared_key' => "transport_" . $this->orderComponent->tourComponent->id,
            'amount' => $this->orderComponent->tourComponent->tour_component_type === 'Included' ? 0 : $this->orderComponent->cost,
            'is_base' => $this->orderComponent->tourComponent->tour_component_type === 'Included',
        ]);
    }

    public function getQuantity(Order|null $order = null): int
    {
        $order = $order ?? $this->orderComponent->orderCustomer->order;
        return $order?->orderTransport()->where('transport_inventory_tour_id', '=', $this->orderComponent->transport_inventory_tour_id)->count() ?? 0;
    }

    public function getItineraryItem(Order|null $order = null): ItineraryItem
    {
        $item = $this->getTourComponent()?->getItineraryItem($this->getQuantity($order));
        if ($this->getStartTime()->isSameDay($this->getEndTime())) {
            $dates = $this->getStartTime()->format('d M Y');
            $lbl_dates = 'Date';
        } else {
            $dates = $this->getStartTime()->format('d M Y') . ' to ' . $this->getEndTime()->format('d M Y');
            $lbl_dates = 'Dates';
        }
        $item->details[$lbl_dates] = $dates;
        return $item;
    }

    public function getStartTime(): Carbon
    {
        $date = $this->orderComponent->tourComponent->inventory->departs_at;
        $override = $this->orderComponent->departs_at_time_override;
        if ($override !== null) {
            $date->setTime($override->hour, $override->minute);
        }
        return $date;
    }

    public function getEndTime(): Carbon
    {
        $date = $this->orderComponent->tourComponent->inventory->arrives_at;
        $override = $this->orderComponent->arrives_at_time_override;
        if ($override !== null) {
            $date->setTime($override->hour, $override->minute);
        }
        return $date;
    }

    public function getCostToCompany(): float
    {
        return $this->orderComponent->purchase_price ?? $this->orderComponent->tourComponent->inventory->local_purchase_price;
    }
}
