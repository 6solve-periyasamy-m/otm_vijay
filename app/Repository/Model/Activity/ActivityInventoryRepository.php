<?php

namespace App\Repository\Model\Activity;

use App\Exceptions\CannotDeleteException;
use App\Models\Activity\ActivityInventory;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\Helper\Enum\ActivityCategory;
use App\Models\Location\Currency;
use App\Models\Quote\Component\QuoteActivity;
use App\Models\Quote\Quote;
use App\Models\Tour\Event;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\ComponentPackageRepository;
use App\Repository\Abstracts\InventoryRepository;
use App\Repository\Interfaces\Manifest\HasActivityManifest;
use App\Repository\Model\Quote\Component\QuoteActivityRepository;
use App\Repository\Reporting\Manifest\ActivityManifestRepository;
use App\Repository\Storage\Itinerary\ItineraryItem;
use App\Repository\Traits\Component\IsActivity;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;
use Settings;

class ActivityInventoryRepository extends InventoryRepository implements HasActivityManifest
{
    use IsActivity;

    private ActivityInventory $inventory;

    public function __construct(ActivityInventory $inventory)
    {
        $this->inventory = $inventory;
    }

    /**
     * @param Carbon $from
     * @param Carbon $to
     * @param ComponentPackageRepository|null $repository
     * @return Collection<ActivityInventory>
     */
    public static function getBetweenDates(Carbon $from, Carbon $to, ComponentPackageRepository|null $repository = null): Collection
    {
        $from->setTime(0, 0);
        $to->setTime(23, 59, 59);
        $inventories = [];
        if (isset($repository)) {
            foreach ($repository->getComponents(false, true, false, false, false) as $inventoryTour) {
                $inventories[] = $inventoryTour->getInventory()->get()->id;
            }
        }
        return ActivityInventory::whereBetween('starts_at', [$from, $to])->whereBetween('ends_at', [$from, $to])->whereNotIn('id', $inventories)->get();
    }

    public function get(): ActivityInventory
    {
        return $this->inventory;
    }

    public function getStartTime(): Carbon|null
    {
        return $this->inventory->starts_at;
    }

    public function getEndTime(): Carbon|null
    {
        return $this->inventory->ends_at;
    }

    public function getAvailableStock(): int
    {
        return $this->getTotalStock() - $this->getUsedStock();
    }

    public function getTotalStock(): int
    {
        return $this->inventory->stock;
    }

    public function getUsedStock(): int
    {
        $query = DB::table('order_activities');
        $query->join('activity_inventory_tours', 'order_activities.activity_inventory_tour_id', '=', 'activity_inventory_tours.id');
        $query->join('activity_inventories', 'activity_inventory_tours.activity_inventory_id', '=', 'activity_inventories.id');
        $query->join('order_customers', 'order_activities.order_customer_id', '=', 'order_customers.id');
        $query->join('orders', 'order_customers.order_id', '=', 'orders.id');
        $query->where('activity_inventories.id', '=', $this->inventory->id);
        $query->where('orders.cancelled', '=', 0);
        $query->whereNull('order_activities.deleted_at');
        return $query->selectRaw("count(order_activities.id) as 'used_stock'")->first()->used_stock;
    }

    public function update(array $data): ActivityInventory
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
        $this->inventory->contractComponents()->forceDelete();
        return $this->inventory->forceDelete();
    }

    public function isDeleted(): bool
    {
        return $this->inventory->trashed();
    }

    public function __toString(): string
    {
        $dateString = "";
        if ($this->inventory->starts_at !== null && $this->inventory->ends_at !== null) {
            $dateString = " (" . f_datetime($this->inventory->starts_at) . " to " . f_datetime($this->inventory->ends_at) . ")";
        }
        return "{$this->inventory->component} - {$this->inventory->ticketType}" . $dateString;
    }

    public function addToTour(Tour $tour, string $tourComponentType, float $price = -1): ?ActivityInventoryTourRepository
    {
        $inventoryTour = ActivityInventoryTour::make([
            'tour_sales_price' => $price == -1 ? $this->inventory->sales_price : $price,
            'tour_component_type' => $tourComponentType,
            'tour_id' => $tour->id,
            'stock_control_active' => $tour->activity_stock_control,
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

    public function addToQuote(Quote $quote, string $tourComponentType, float $price = -1): ?QuoteActivityRepository
    {
        $inventoryTour = QuoteActivity::make([
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

    public function isStockControlActive(): bool
    {
        return false;
    }

    public function hasEnoughStock(int $amount = 1): bool
    {
        return $this->getAvailableStock() >= $amount;
    }

    public function getSalesPrice(): ?float
    {
        return $this->inventory->sales_price;
    }

    public function getActivityManifest(): Collection|array
    {
        return $this->inventory->orders()->with(ActivityManifestRepository::getRelations())->get();
    }

    public static function find($id): ActivityInventory|null
    {
        return ActivityInventory::find($id);
    }

    public function getLocalPurchasePrice(): ?float
    {
        return Settings::convertCurrency($this->getPurchasePrice(), $this->getCurrency()) ?? $this->getPurchasePrice() ?? 0;
    }

    public function getPurchasePriceString(): string
    {
        return f_currency($this->getPurchasePrice(),$this->getCurrency());
    }

    public function getItineraryItem(int|null $quantity = null): ItineraryItem
    {
        $details = [
            'Dates' => $this->inventory->starts_at->format('d M Y') . ' to ' . $this->inventory->ends_at->format('d M Y'),
            'Venue' => $this->inventory->component->address->name,
            'Ticket' => $this->inventory->component->name,
            'Quantity' => $quantity,
            'Description' => $this->inventory->inventory_description ?: ($this->inventory->component->description ?? ''),
        ];
        if ($quantity === null) { unset($details['Quantity']); }
        return new ItineraryItem(
            $this->inventory->component->name,
            $this->inventory->component->activity_category === ActivityCategory::MAIN ? 'Event' : 'Inclusions',
            $this->inventory->starts_at->unix(),
            $details,
        );
    }

    /**
     * Get all inventory that match this one (excluding dates)
     *
     * @return Collection<ActivityInventory>|ActivityInventory[]
     */
    private function getMatchingInventory(): Collection|array
    {
        return $this->inventory->activity->inventory()
            ->where('ticket_type_id', '=', $this->inventory->ticket_type_id)
            ->with(['ticketType',])
            ->get();
    }

    public function getStartingAt(Carbon $start): ActivityInventory
    {
        $end = $start->copy()->addDays(diff_in_nights($this->inventory->starts_at, $this->inventory->ends_at));
        foreach ($this->getMatchingInventory() as $inventory) {
            if ($inventory->starts_at->isSameDay($start)
                && $inventory->ends_at->isSameDay($end)) {
                return $inventory;
            }
        }
        $duplicate = $this->inventory->replicate();
        $duplicate->starts_at = $start;
        $duplicate->ends_at = $end;
        $duplicate->save();
        return $duplicate;
    }
}
