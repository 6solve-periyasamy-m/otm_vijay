<?php

namespace App\Repository\Model\Accommodation;

use App\Exceptions\CannotDeleteException;
use App\Models\Accommodation\AccommodationInventory;
use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Location\Currency;
use App\Models\Quote\Component\QuoteAccommodation;
use App\Models\Quote\Quote;
use App\Models\Tour\Event;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\ComponentPackageRepository;
use App\Repository\Abstracts\InventoryRepository;
use App\Repository\Interfaces\Manifest\HasRoomingList;
use App\Repository\Model\Quote\Component\QuoteAccommodationRepository;
use App\Repository\Storage\Itinerary\ItineraryItem;
use App\Repository\Traits\Component\IsAccommodation;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Settings;

class AccommodationInventoryRepository extends InventoryRepository implements HasRoomingList
{
    use IsAccommodation;

    private AccommodationInventory $inventory;

    public function __construct(AccommodationInventory $inventory)
    {
        $this->inventory = $inventory;
    }

    public static function validateStockParent(int|null $parent, AccommodationInventory|null $inventory): bool
    {
        if ($parent === null || $inventory === null) { return true; }
        $parent = AccommodationInventory::find($parent);
        if ($parent === null || $inventory->id === $parent->id) { return false; }
        $parents = [];
        do {
            if (in_array($parent->id, $parents)) { return false; }
            $parents[] = $parent->id;
            $parent = $parent->stockParent;
        } while ($parent !== null);
        return true;
    }

    public function validateParent(int|null $parent): bool
    {
        return static::validateStockParent($parent, $this->inventory);
    }

    /**
     * @param Carbon $from
     * @param Carbon $to
     * @param ComponentPackageRepository|null $repository
     * @return Collection<AccommodationInventory>
     */
    public static function getBetweenDates(Carbon $from, Carbon $to, ComponentPackageRepository|null $repository = null): Collection
    {
        $from->setTime(0, 0);
        $to->setTime(23, 59, 59);
        $inventories = [];
        if (isset($repository)) {
            foreach ($repository->getComponents(true, false, false, false, false) as $inventoryTour) {
                $inventories[] = $inventoryTour->getInventory()->get()->id;
            }
        }
        return AccommodationInventory::whereBetween('check_in', [$from, $to])->whereBetween('check_out', [$from, $to])->whereNotIn('id', $inventories)->get();
    }

    public function get(): AccommodationInventory
    {
        return $this->inventory;
    }

    public function getStartTime(): Carbon
    {
        return $this->inventory->check_in;
    }

    public function getEndTime(): Carbon
    {
        return $this->inventory->check_out;
    }

    public function getHotelName()
    {
        return $this->inventory;
    }

    public function getAvailableStock(): int
    {
        if ($this->inventory->stock_parent_id !== null && $this->inventory->stock_parent_id !== $this->inventory->id) {
            return $this->inventory->stockParent->repository->getAvailableStock();
        }
        return $this->getTotalStock() - $this->getTotalUsedStock();
    }

    public function getTotalStock(): int
    {
        return $this->inventory->stock_parent_id !== null ? $this->inventory->stockParent->repository->getTotalStock() : $this->inventory->stock;
    }

    public function getTotalUsedStock(): int
    {
        $used = $this->getUsedStock();
        foreach ($this->inventory->stockChildren as $children) {
            $used += $children->used_stock;
        }
        return $used;
    }

    public function getUsedStock(): int
    {
        $used = 0;
        foreach ($this->inventory->tourComponents as $component) {
            foreach ($component->orders as $orderComponent) {
                if (!$orderComponent->cancelled) $used++;
            }
        }
        return $used;
    }

    public function update(array $data): AccommodationInventory
    {
        $this->inventory->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->inventory->save();
    }

    public function getDependants(): int
    {
        return $this->inventory->tourComponents()->count() + $this->inventory->quoteComponents()->count();
    }

    /**
     * @throws CannotDeleteException
     */
    public function delete(bool $unlink = false): bool
    {
        if ($this->inventory->tourComponents()->count() > 0) {
            throw new CannotDeleteException('Cannot delete inventory as it has dependants');
        }
        $children = $this->inventory->stockChildren()->count();
        if ($children > 0) {
            throw new CannotDeleteException("This inventory has {$children} stock children, and cannot be deleted");
        }
        if ($unlink) {
            foreach ($this->inventory->stockChildren as $child) {
                $child->stock_parent_id = null;
                $child->save();
            }
        }
        $this->inventory->contractComponents()->forceDelete();
        return $this->inventory->forceDelete();
    }

    public function isDeleted(): bool
    {
        return $this->inventory->trashed();
    }

    public function __toString(): string
    {
        return "{$this->inventory->component} - {$this->inventory->roomType} {$this->inventory->boardType} (" . f_datetime($this->inventory->check_in) . " to " . f_datetime($this->inventory->check_out) . ")";
    }

    public function addToTour(Tour $tour, string|null $tourComponentType = 'Included', float $price = -1): ?AccommodationInventoryTourRepository
    {
        $inventoryTour = AccommodationInventoryTour::make([
            'tour_sales_price' => $price == -1 ? $this->inventory->sales_price : $price,
            'tour_component_type' => $tourComponentType,
            'tour_id' => $tour->id,
            'stock_control_active' => $tour->accommodation_stock_control,
        ]);
        $this->inventory->tourComponents()->save($inventoryTour);
        return $inventoryTour->repository;
    }

    public function addToEvent(Event $event, string|null $tourComponentType = 'Included', float|null $price = null): void
    {
        foreach ($event->tours as $tour) {
            $this->addToTour($tour, $tourComponentType, $price ?? -1);
        }
    }

    public function addToQuote(Quote $quote, string $tourComponentType, float $price = -1): ?QuoteAccommodationRepository
    {
        $inventoryTour = QuoteAccommodation::make([
            'tour_sales_price' => $price == -1 ? $this->inventory->sales_price ?? 0 : $price,
            'tour_component_type' => $tourComponentType,
            'quote_id' => $quote->id,
        ]);
        $this->inventory->tourComponents()->save($inventoryTour);
        return $inventoryTour->repository;
    }

    public function getPurchasePrice(): float
    {
        return $this->inventory->purchase_price ?? 0.0;
    }

    public function getCurrency(): Currency
    {
        return $this->inventory->currency ?? $this->inventory->component->currency ?? Settings::currency();
    }

    public function getRoomingList(): Collection|array
    {
        return $this->inventory->orderComponents()->with(
            'group',
            'group.orderCustomers',
            'group.orderCustomers.customer',
            'accommodationInventoryTour',
            'accommodationInventoryTour.inventory',
            'accommodationInventoryTour.accommodationInventory.accommodation',
            'accommodationInventoryTour.accommodationInventory.roomType',
            'accommodationInventoryTour.accommodationInventory.boardType'
        )->get();
    }

    public function getRoomingListt(): Collection|array
    {
        return $this->inventory->orderComponents()->get();
    }
    public function getSalesPrice(): ?float
    {
        return $this->inventory->sales_price;
    }

    public function isStockControlActive(): bool
    {
        return false;
    }

    public function hasEnoughStock(int $amount = 1): bool
    {
        return $this->getAvailableStock() >= $amount;
    }

    public static function find($id): AccommodationInventory|null
    {
        return AccommodationInventory::find($id);
    }

    public function getLocalPurchasePrice(): ?float
    {
        return Settings::convertCurrency($this->getPurchasePrice(), $this->getCurrency()) ?? $this->getPurchasePrice() ?? 0;
    }

    public function getPurchasePriceString(): string
    {
        return f_currency($this->getPurchasePrice(), $this->getCurrency());
    }
    public function getItineraryItem(int|null $quantity = null): ItineraryItem
    {
        $details = [
            'Check In' => $this->inventory->check_in->format('d M Y'),
            'Check Out' => $this->inventory->check_out->format('d M Y'),
            'No of Nights' => diff_in_nights($this->inventory->check_in, $this->inventory->check_out),
            'Address' => $this->inventory->component->address,
            'Room Type' => $this->inventory->roomType->name,
            'Board Type' => $this->inventory->boardType->name,
            'Quantity' => $quantity,
            'Description' => $this->inventory->component->description,
        ];
        if ($quantity === null) { unset($details['Quantity']); }
        return new ItineraryItem(
            $this->inventory->component->name,
            'Hotel',
            $this->inventory->check_in->unix(),
            $details,
        );
    }

    public function getNightsInTour(Tour $tour): int
    {
        $nights = [];
        foreach ($tour->accommodationInventoryTours as $component) {
            if ($component->inventory->accommodation_id !== $this->inventory->accommodation_id) { continue; }
            foreach (CarbonPeriod::create($component->inventory->check_in,'1 day', $component->inventory->check_out) as $date) {
                $nights[$date->format('Y-m-d')] = true;
            }
        }
        $count = 0;
        foreach ($nights as $night => $active) {
            if ($active) { $count++; }
        }
        return $count;
    }

    /**
     * Compare two AccommodationInventory based on their start and end times
     *
     * @param AccommodationInventory $a
     * @param AccommodationInventory $b
     * @return int Returns: 1 if a is after, 0 if same, -1 if b is after
     */
    public static function compareTwo(AccommodationInventory $a, AccommodationInventory $b): int
    {
        if ($a->check_in->isSameDay($b->check_in)) {
            if ($a->check_out->isSameDay($b->check_out)) { return 0; }
            if ($a->check_out->gt($b->check_out)) { return 1; }
            return -1;
        }
        if ($a->check_in->gt($b->check_in)) { return 1; }
        return -1;
    }

    private function getMatchingInventory(): Collection|array
    {
        return $this->inventory->accommodation->inventory()
                ->where('room_type_id', '=', $this->inventory->room_type_id)
                ->where('board_type_id', '=', $this->inventory->board_type_id)
                ->where('room_category_id', '=', $this->inventory->room_category_id)
                ->with(['roomType', 'boardType', 'category'])
                ->get();
    }

    public function getStartingAt(Carbon $start): AccommodationInventory
    {
        $end = $start->copy()->addDays(diff_in_nights($this->inventory->check_in, $this->inventory->check_out));
        foreach ($this->getMatchingInventory() as $inventory) {
            if ($inventory->check_in->isSameDay($start)
                && $inventory->check_out->isSameDay($end)) {
                return $inventory;
            }
        }
        $duplicate = $this->inventory->replicate();
        $duplicate->check_in = $start;
        $duplicate->check_out = $end;
        $duplicate->save();
        return $duplicate;
    }
}
