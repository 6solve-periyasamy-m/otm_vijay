<?php

namespace App\Repository\Model\Accommodation;

use App\Models\Accommodation\AccommodationInventory;
use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Quote\Component\QuoteAccommodation;
use App\Models\Quote\Quote;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\ComponentPackageRepository;
use App\Repository\Abstracts\InventoryRepository;
use App\Repository\Interfaces\Manifest\HasRoomingList;
use App\Repository\Model\Quote\Component\QuoteAccommodationRepository;
use App\Repository\Storage\Itinerary\ItineraryItem;
use App\Repository\Traits\Component\IsAccommodation;
use Carbon\Carbon;
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

    public static function getBetweenDates(Carbon $from, Carbon $to, ComponentPackageRepository $repository = null): Collection
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

    public function delete(): bool
    {
        return $this->inventory->delete();
    }

    public function isDeleted(): bool
    {
        return $this->inventory->trashed();
    }

    public function __toString(): string
    {
        return "{$this->inventory->component} - {$this->inventory->roomType} {$this->inventory->boardType} (" . f_datetime($this->inventory->check_in) . " to " . f_datetime($this->inventory->check_out) . ")";
    }

    public function addToTour(Tour $tour, string $tourComponentType, float $price = -1): ?AccommodationInventoryTourRepository
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
        return $this->inventory->purchase_price;
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
        return true;
    }

    public static function find($id): AccommodationInventory|null
    {
        return AccommodationInventory::find($id);
    }

    public function getLocalPurchasePrice(): ?float
    {
        return Settings::convertCurrency($this->getPurchasePrice(), $this->inventory->component->currency) ?? $this->getPurchasePrice() ?? 0;
    }

    public function getPurchasePriceString(): string
    {
        return f_currency($this->getPurchasePrice(), $this->inventory->component->currency);
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
            $details,
        );
    }
}
