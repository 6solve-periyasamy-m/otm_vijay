<?php

namespace App\Repository\Storage\Rooming;

use App\Models\Accommodation\Accommodation;
use App\Models\Accommodation\AccommodationInventory;
use App\Models\Accommodation\BoardType;
use App\Models\Accommodation\RoomCategory;
use App\Models\Accommodation\RoomType;
use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\Order;
use App\Models\Quote\Component\QuoteAccommodation;
use App\Models\Quote\Quote;
use App\Repository\Storage\Itinerary\ItineraryItem;
use Carbon\Carbon;

class AccommodationByDateStorage
{
    /** @var array<int, AccommodationInventory> */
    public array $inventory;

    public function __construct(
        public Accommodation $accommodation,
        public RoomType $room,
        public BoardType $board,
        public RoomCategory|null $category,
    )
    {
        $this->inventory = [];
    }

    public static function createFromInventory(AccommodationInventory $inventory): self
    {
        $instance = new self($inventory->accommodation, $inventory->roomType, $inventory->boardType, $inventory->category);
        $instance->addRoom($inventory);
        return $instance;
    }

    public function getInventoryOnNight(Carbon $night): AccommodationInventory|null
    {
        // Date needs to be at basically midnight
        $night = $night->setTime(23, 59, 59);
        foreach ($this->inventory as $inventory) {
            if ($inventory->check_in->isBefore($night) && $inventory->check_out->isAfter($night)) {
                return $inventory;
            }
        }
        return null;
    }

    public function sort(): void
    {
        usort($this->inventory,
            static function (AccommodationInventory $a, AccommodationInventory $b) {
                return $a->check_in->unix() <=> $b->check_out->unix();
            }
        );
    }

    public function addRoom(AccommodationInventory $inventory): bool
    {
        if ($this->matches($inventory)) {
            $this->inventory[$inventory->id] = $inventory;
            return true;
        }
        $this->sort();
        return false;
    }

    public function matches(AccommodationInventory $inventory): bool
    {
        return $inventory->accommodation_id === $this->accommodation->id
                && $inventory->room_type_id === $this->room->id
                && $inventory->board_type_id === $this->board->id
                && $inventory->room_category_id === $this->category?->id;
    }

    /**
     * Get a list of itinerary items for a quote
     *
     * @param Quote $quote The quote the itinerary is generated for. Used for getting quantities
     * @return ItineraryItem[]
     */
    public function getItineraryLinesForQuote(Quote $quote): array
    {
        $this->sort();
        $items = [];
        $start = null;
        $prev = null;
        foreach ($this->inventory as $inventory) {
            if ($start === null) { $start = $inventory; }
            if ($prev !== null && abs($inventory->check_in->diffInDays($prev->check_out)) > 1) {
                $itinerary = $start->repository->getItineraryItem($this->findQuoteAccommodation($quote, $start)?->quantity);
                $itinerary->details['Check Out'] = $prev->check_out->format('d M Y');
                $items[] = $itinerary;
                $start = $inventory;
            }
            $prev = $inventory;
        }
        $itinerary = $start->repository->getItineraryItem($this->findQuoteAccommodation($quote, $start)?->quantity);
        $itinerary->details['Check Out'] = $prev->check_out->format('d M Y');
        $items[] = $itinerary;
        return $items;
    }

    public function getItineraryLinesForOrder(Order $order): array
    {
        $this->sort();
        $items = [];
        $start = null;
        $prev = null;
        foreach ($this->inventory as $inventory) {
            if ($start === null) { $start = $inventory; }
            if ($prev !== null && abs($inventory->check_in->diffInDays($prev->check_out)) > 1) {
                $itinerary = $start->repository->getItineraryItem($this->getOrderQuantity($order, $start));
                $itinerary->details['Check Out'] = $prev->check_out->format('d M Y');
                $items[] = $itinerary;
                $start = $inventory;
            }
            $prev = $inventory;
        }
        $itinerary = $start->repository->getItineraryItem($this->getOrderQuantity($order, $start));
        $itinerary->details['Check Out'] = $prev->check_out->format('d M Y');
        $items[] = $itinerary;
        return $items;
    }

    /**
     * Find a specific Quote inventory line for an inventory
     *
     * Warning: Cannot use Eloquent/MySQL queries for it, since they cannot run on instanced models that are not saved
     * in the database. Since quote documents are built from stored JSON on a sent quote, the quote does not have an ID
     *
     * @param Quote $quote
     * @param AccommodationInventory $inventory
     * @return QuoteAccommodation|null
     */
    private function findQuoteAccommodation(Quote $quote, AccommodationInventory $inventory): ?QuoteAccommodation
    {
        foreach ($quote->accommodation as $component) {
            if ($component->accommodation_inventory_id === $inventory->id) {
                return $component;
            }
        }
        return null;
    }

    /**
     * Get the quantity of a specific inventory on an order
     *
     * @param Order $order
     * @param AccommodationInventory $inventory
     * @return int
     */
    private function getOrderQuantity(Order $order, AccommodationInventory $inventory): int
    {
        $quantity = 0;
        foreach ($order->orderCustomers as $customer) {
            foreach ($customer->orderAccommodation as $room) {
                if ($room->tourComponent->accommodation_inventory_id === $inventory->id) {
                    $quantity++;
                }
            }
        }
        return $quantity;
    }
}
