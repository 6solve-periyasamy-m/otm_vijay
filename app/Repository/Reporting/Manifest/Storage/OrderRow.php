<?php

namespace App\Repository\Reporting\Manifest\Storage;

use App\Models\AdditionalCost;
use App\Models\Helper\Enum\OrderStatus;
use App\Models\Location\Address;
use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\Component\OrderActivity;
use App\Models\Order\Component\OrderFlight;
use App\Models\Order\Component\OrderMerchandise;
use App\Models\Order\Component\OrderTransport;
use App\Models\Order\Order;
use App\Repository\Storage\Order\MergedAccommodation;
use App\Repository\Traits\HasCustomAttributes;
use Carbon\Carbon;

/**
 * @property-read int|null $nights Number of nights the component spans
 */
class OrderRow
{
    use HasCustomAttributes;

    /**
     * @param string|null $event Name of the event the order is related to
     * @param string $reference Booking reference for the order
     * @param Carbon $ordered When the order was placed
     * @param OrderStatus $status Current status of the order
     * @param string|null $orderInternal Internal notes for the order
     * @param string|null $orderExternal External notes for the order
     * @param string|null $organization Organization who placed the order, or null if independent
     * @param string|null $agent Agent who placed the order, or null if independent
     * @param string|null $consultant Consultant who manages the order, or null if unmanaged
     * @param string $leadBooker Name of the lead booker
     * @param string|null $location Address of the component
     * @param string $component Type of component (Accommodation, Activity, etc.)
     * @param string $tourComponentType Tour Component Type of component (Included, Add-on, etc.)
     * @param string $description Description of the component
     * @param Carbon|null $start Start date/time of component
     * @param Carbon|null $end End date/time of component
     * @param int|null $travellers Number of travellers involved with component
     * @param int $quantity Quantity ordered within booking
     * @param string $currency Currency sold in
     * @param float $purchasePrice Cost to company for component
     * @param float $salesPrice Cost to customer for component
     * @param string|null $internalNotes Internal notes for the inventory
     * @param string|null $externalNotes External notes for the inventory
     */
    public function __construct(
        public readonly string|null $event,
        public readonly string      $reference,
        public readonly Carbon      $ordered,
        public readonly OrderStatus $status,
        public readonly string|null $orderInternal,
        public readonly string|null $orderExternal,
        public readonly string|null $organization,
        public readonly string|null $agent,
        public readonly string|null $consultant,
        public readonly string      $leadBooker,
        public readonly string|null $location,
        public readonly string      $component,
        public readonly string      $tourComponentType,
        public readonly string      $description,
        public readonly Carbon|null $start,
        public readonly Carbon|null $end,
        public readonly int|null         $travellers,
        public          int         $quantity,
        public readonly string      $currency,
        public readonly float       $purchasePrice,
        public readonly float       $salesPrice,
        public readonly string|null $internalNotes,
        public readonly string|null $externalNotes,
    )
    {
    }

    /**
     * Get all rows for a specified order
     *
     * @param Order $order
     * @return OrderRow[]
     */
    public static function fromOrder(Order $order): array
    {
        $rows = [];
        /** @var array{merged: MergedAccommodation, quantity: int}[] $accommodationData */
        $accommodationData = [];

        foreach ($order->repository->getMergedAccommodation() as $merged) {
            $found = false;
            foreach ($accommodationData as $key => $data) {
                if ($data['merged']->equals($merged)) {
                    ++$data['quantity'];
                    $accommodationData[$key] = $data;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $accommodationData[] = ['merged' => $merged, 'quantity' => 1];
            }
        }

        foreach ($accommodationData as $data) {
            $rows[] = $data['merged']->getOrderRow($order, $data['quantity']);
        }

        foreach ($order->orderActivities()->groupBy('activity_inventory_tour_id')->get() as $component) {
            $rows[] = self::fromActivity($component);
        }

        foreach ($order->orderFlights()->groupBy('flight_inventory_tour_id')->get() as $component) {
            $rows[] = self::fromFlight($component);
        }

        foreach ($order->orderTransport()->groupBy('transport_inventory_tour_id')->get() as $component) {
            $rows[] = self::fromTransport($component);
        }

        foreach ($order->orderMerchandise()->groupBy('merchandise_inventory_tour_id')->get() as $component) {
            $rows[] = self::fromMerchandise($component);
        }

        foreach ($order->tour->costs as $cost) {
            $rows[] = self::fromCost($cost, $order);
        }

        return $rows;
    }

    public static function fromAccommodation(OrderAccommodation $component): self
    {
        $order = $component->group->orderCustomers()->first()->order;
        $inventory = $component->tourComponent?->inventory;
        return new self(
            $order->tour?->event?->name,
            $order->booking_reference,
            $order->ordered_on,
            $order->status,
            $order->internal_notes,
            $order->external_notes,
            $order->organization?->name,
            $order->agent?->name,
            $order->consultant?->name,
            $order->leadBooker->lead_booker_name,
            self::formatAddress($inventory?->component?->address),
            "Accommodation",
            $component->tourComponent?->tour_component_type,
            $inventory?->component->name . ' - ' . $inventory?->category?->name,
            $inventory?->check_in,
            $inventory?->check_out,
            $component->group?->orderCustomers()->count(),
            $order->repository->getQuantity($component),
            $inventory->component?->currency?->code ?? setting('system.currency'),
            $inventory->purchase_price,
            $component->tourComponent?->tour_component_type === 'Included' ? 0 : $component->tourComponent?->tour_sales_price,
            $inventory->internal_notes,
            $inventory->external_notes,
        );
    }

    public static function fromActivity(OrderActivity $component): self
    {
        $order = $component->orderCustomer?->order;
        $inventory = $component->tourComponent?->inventory;
        return new self(
            $order->tour?->event?->name,
            $order->booking_reference,
            $order->ordered_on,
            $order->status,
            $order->internal_notes,
            $order->external_notes,
            $order->organization?->name,
            $order->agent?->name,
            $order->consultant?->name,
            $order->leadBooker->lead_booker_name,
            self::formatAddress($inventory?->component?->address),
            "Activity",
            $component->tourComponent?->tour_component_type,
            $inventory?->component->name,
            $inventory?->starts_at,
            $inventory?->ends_at,
            null,
            $order->repository->getQuantity($component),
            $inventory->component?->currency?->code ?? setting('system.currency'),
            $inventory->purchase_price,
            $component->tourComponent?->tour_component_type === 'Included' ? 0 : $component->tourComponent?->tour_sales_price,
            $inventory->internal_notes,
            $inventory->external_notes,
        );
    }

    public static function fromFlight(OrderFlight $component): self
    {
        $order = $component->orderCustomer?->order;
        $inventory = $component->tourComponent?->inventory;
        return new self(
            $order->tour?->event?->name,
            $order->booking_reference,
            $order->ordered_on,
            $order->status,
            $order->internal_notes,
            $order->external_notes,
            $order->organization?->name,
            $order->agent?->name,
            $order->consultant?->name,
            $order->leadBooker->lead_booker_name,
            self::formatAddress($inventory?->component?->departureAirport?->address),
            "Flight",
            $component->tourComponent?->tour_component_type,
            $inventory?->component->departureAirport?->name . ' to ' . $inventory?->component->arrivalAirport?->name,
            $inventory?->departs_at,
            $inventory?->arrives_at,
            null,
            $order->repository->getQuantity($component),
            $inventory->component?->currency?->code ?? setting('system.currency'),
            $inventory->purchase_price,
            $component->tourComponent?->tour_component_type === 'Included' ? 0 : $component->tourComponent?->tour_sales_price,
            $inventory->internal_notes,
            $inventory->external_notes,
        );
    }

    public static function fromTransport(OrderTransport $component): self
    {
        $order = $component->orderCustomer?->order;
        $inventory = $component->tourComponent?->inventory;
        return new self(
            $order->tour?->event?->name,
            $order->booking_reference,
            $order->ordered_on,
            $order->status,
            $order->internal_notes,
            $order->external_notes,
            $order->organization?->name,
            $order->agent?->name,
            $order->consultant?->name,
            $order->leadBooker->lead_booker_name,
            self::formatAddress($inventory?->component?->departureAddress),
            "Transport",
            $component->tourComponent?->tour_component_type,
            $inventory?->component->name,
            $inventory?->departs_at,
            $inventory?->arrives_at,
            null,
            $order->repository->getQuantity($component),
            $inventory->component?->currency?->code ?? setting('system.currency'),
            $inventory->purchase_price,
            $component->tourComponent?->tour_component_type === 'Included' ? 0 : $component->tourComponent?->tour_sales_price,
            $inventory->internal_notes,
            $inventory->external_notes,
        );
    }

    public static function fromMerchandise(OrderMerchandise $component): self
    {
        $order = $component->orderCustomer?->order;
        $inventory = $component->tourComponent?->inventory;
        return new self(
            $order->tour?->event?->name,
            $order->booking_reference,
            $order->ordered_on,
            $order->status,
            $order->internal_notes,
            $order->external_notes,
            $order->organization?->name,
            $order->agent?->name,
            $order->consultant?->name,
            $order->leadBooker->lead_booker_name,
            null,
            "Merchandise",
            $component->tourComponent?->tour_component_type,
            $inventory?->component->name,
            null,
            null,
            null,
            $order->repository->getQuantity($component),
            setting('system.currency'),
            $inventory->purchase_price,
            $component->tourComponent?->tour_component_type === 'Included' ? 0 : $component->tourComponent?->tour_sales_price,
            $inventory->internal_notes,
            $inventory->external_notes,
        );
    }

    public static function fromCost(AdditionalCost $cost, Order $order): self
    {
        return new self(
            $order->tour?->event?->name,
            $order->booking_reference,
            $order->ordered_on,
            $order->status,
            $order->internal_notes,
            $order->external_notes,
            $order->organization?->name,
            $order->agent?->name,
            $order->consultant?->name,
            $order->leadBooker->lead_booker_name,
            null,
            "Cost",
            $cost->per_customer ? 'Per-Customer' : 'Whole Package',
            $cost->name,
            null,
            null,
            $cost->per_customer ? $order->orderCustomers()->count() : 1,
            1,
            setting('system.currency'),
            $cost->amount,
            0,
            null,
            null,
        );
    }

    public function getNightsAttribute(): int|null
    {
        if ($this->start === null && $this->end === null) {
            return null;
        }
        if ($this->start === null || $this->end === null) {
            return 1;
        }
        return (int)diff_in_nights($this->start, $this->end);
    }

    public static function formatAddress(Address|null $address): string|null
    {
        return $address?->region;
    }
}
