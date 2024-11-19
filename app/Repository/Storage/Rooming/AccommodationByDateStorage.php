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
use App\Models\Tour\Tour;
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
    private function getItineraryLinesForQuote(Quote $quote): array
    {
        $this->sort();
        $items = [];
        foreach ($this->inventory as $inventory) {
            $quantity = $this->findQuoteAccommodation($quote, $inventory)?->quantity;
            $key = null;
            $data = [];
            foreach ($items as $key => $item) {
                if ($item['start']->isSameDay($inventory->check_in)
                    && $item['end']->isSameDay($inventory->check_out))
                {
                    $data = $item;
                    break;
                }
            }
            if (!empty($key) && !empty($data)) {
                $data['quantity'] += $quantity;
                $items[$key] = $data;
            } else {
                $items[] = [
                    'start' => $inventory->check_in,
                    'end' => $inventory->check_out,
                    'quantity' => $quantity,
                ];
            }
        }
        return $items;
    }

    private function getItineraryLinesForOrder(Order $order): array
    {
        $this->sort();
        $items = [];
        foreach ($this->inventory as $inventory) {
            $quantity = $this->getOrderQuantity($order, $inventory);
            $key = null;
            $data = [];
            foreach ($items as $key => $item) {
                if ($item['start']->isSameDay($inventory->check_in)
                    && $item['end']->isSameDay($inventory->check_out))
                {
                    $data = $item;
                    break;
                }
            }
            if (!empty($key) && !empty($data)) {
                $data['quantity'] += $quantity;
                $items[$key] = $data;
            } else {
                $items[] = [
                    'start' => $inventory->check_in,
                    'end' => $inventory->check_out,
                    'quantity' => $quantity,
                ];
            }
        }
        return $items;
    }

    public function getItineraryLines(Quote|Order $model): array
    {
        $first = reset($this->inventory);
        if (!($first instanceof AccommodationInventory)) {
            return [];
        }
        $baseItineraryItem = $first->repository->getItineraryItem();
        if ($model instanceof Order) {
            $items = $this->getItineraryLinesForOrder($model);
        } else {
            $items = $this->getItineraryLinesForQuote($model);
        }
        uasort($items, static function ($a, $b) {
            return $a['start'] <=> $b['start'];
        });
        $itineraryItems = [];
        $blocks = [];
        foreach ($items as $item) {
            if (empty($blocks)) {
                $blocks[] = $item;
                continue;
            }
            $quantity = $item['quantity'];
            foreach ($blocks as $key => $block) {
                // Clear all blocks that are no longer continuous
                if (abs($block['end']->diffInDays($item['start'])) > 1) {
                    $itineraryItems[] = $this->cloneItineraryItem($baseItineraryItem, $block['start'], $block['end'], $block['quantity']);
                    unset($blocks[$key]);
                    continue;
                }


                // If block has equal quantity, update end time
                if ($block['quantity'] === $quantity) {
                    $block['end'] = $item['end'];
                    $blocks[$key] = $block;
                }
                // If block has more quantity, clear amount above quantity
                else if ($block['quantity'] > $quantity) {
                    $itineraryItems[] = $this->cloneItineraryItem($baseItineraryItem, $block['start'], $block['end'], $block['quantity'] - $quantity);
                    $block['quantity'] = $quantity;
                    $block['end'] = $item['end'];
                    $blocks[$key] = $block;
                }
                // If block has less quantity, make new block
                else if ($block['quantity'] < $quantity) {
                    $block['end'] = $item['end'];
                    $blocks[$key] = $block;
                    $item['quantity'] = $quantity - $block['quantity'];
                    $blocks[] = $item;
                }
            }

            // If the blocks are now empty, make a new block with this item
            if (empty($blocks)) {
                $blocks[] = $item;
            }
        }
        // Finalize all remaining blocks
        foreach ($blocks as $block) {
            $itineraryItems[] = $this->cloneItineraryItem($baseItineraryItem, $block['start'], $block['end'], $block['quantity']);
        }
        return $itineraryItems;
    }

    private function cloneItineraryItem(ItineraryItem $item, Carbon $start, Carbon $end, int $quantity): ItineraryItem
    {
        $item = $item->clone();
        $item->sortKey = $start->unix();
        $item->details['Check In'] = $start->format('d M Y');
        $item->details['Check Out'] = $end->format('d M Y');
        $item->details['No of Nights'] = diff_in_nights($start, $end);
        $item->details['Quantity'] = $quantity;
        return $item;
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
        $seen = [];
        foreach ($order->orderCustomers as $customer) {
            foreach ($customer->orderAccommodation as $room) {
                if ($room->tourComponent->accommodation_inventory_id === $inventory->id) {
                    if (in_array($room->id, $seen)) { continue; }
                    $seen[] = $room->id;
                    $quantity++;
                }
            }
        }
        return $quantity;
    }

    public function addToQuote(Quote $quote, int|null $quantity = null): void
    {
        foreach ($this->inventory as $inventory) {
            $item = $inventory->repository->addToQuote($quote, 'Included');
            $item?->update(['quantity' => $quantity]);
        }
    }

    public function addToTour(Tour $tour): void
    {
        foreach ($this->inventory as $inventory) {
            $inventory->repository->addToTour($tour, 'Included');
        }
    }
}
