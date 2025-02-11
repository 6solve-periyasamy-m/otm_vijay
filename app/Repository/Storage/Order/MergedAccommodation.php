<?php

namespace App\Repository\Storage\Order;

use App\Models\Accommodation\Accommodation;
use App\Models\Accommodation\AccommodationInventory;
use App\Models\Accommodation\BoardType;
use App\Models\Accommodation\RoomCategory;
use App\Models\Accommodation\RoomType;
use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\Order;
use App\Repository\Reporting\Manifest\Storage\OrderRow;
use Carbon\Carbon;

class MergedAccommodation
{
    public function __construct(
        public Accommodation $hotel,
        public RoomType $room,
        public BoardType $board,
        public RoomCategory|null $category,
        public Carbon $start,
        public Carbon $end,
        public int $travellers,
        public float $purchase,
        public float|null $sale,
        public string $tourComponentType = 'Included',
        public string|null $internal = null,
        public string|null $external = null,
    )
    {}

    public static function make(OrderAccommodation $component): MergedAccommodation
    {
        $inventory = $component->tourComponent->inventory;
        return new self(
            $inventory->accommodation,
            $inventory->roomType,
            $inventory->boardType,
            $inventory->category,
            $inventory->check_in,
            $inventory->check_out,
            $component->group->orderCustomers()->count(),
            $inventory->local_purchase_price,
            $component->tourComponent->tour_sales_price,
            $component->tourComponent->tour_component_type,
            $component->tourComponent->inventory->internal_notes,
            $component->tourComponent->inventory->external_notes,
        );
    }


    public function addToMerge(OrderAccommodation $component): bool
    {
        $inventory = $component->tourComponent->inventory;
        if ($this->matches($inventory) &&
            $this->end->isSameDay($inventory->check_in) &&
            !$this->start->isSameDay($inventory->check_in) &&
            $component->group->orderCustomers()->count() === $this->travellers
        ) {
            $this->end = $inventory->check_out;
            return true;
        }
        return false;
    }

    private function matches(AccommodationInventory $inventory): bool
    {
        return $inventory->accommodation_id === $this->hotel->id &&
                $inventory->room_type_id === $this->room->id &&
                $inventory->board_type_id === $this->board->id &&
                $inventory->room_category_id === $this->category?->id;
    }

    public function equals(self $merged): bool
    {
        return $this->hotel->id === $merged->hotel->id &&
                $this->room->id === $merged->room->id &&
                $this->board->id === $merged->board->id &&
                $this->category->id === $merged->category->id &&
                $this->start->isSameDay($merged->start) &&
                $this->end->isSameDay($merged->end);
    }

    public function getOrderRow(Order $order): OrderRow
    {
        return new OrderRow(
            $order->tour?->event?->name,
            $order->booking_reference,
            $order->ordered_on,
            $order->status,
            $order->organization?->name,
            $order->agent?->name,
            $order->consultant?->name,
            $order->leadBooker->lead_booker_name,
            OrderRow::formatAddress($this->hotel?->address),
            "Accommodation",
            $this->tourComponentType,
            $this->hotel->name . ' - ' . $this->category?->name,
            $this->start,
            $this->end,
            $this->travellers,
            1,
            setting('system.currency'),
            $this->purchase,
            $this->tourComponentType === 'Included' ? 0 : ($this->sale ?? 0.0),
            $this->internal,
            $this->external,
        );
    }
}
