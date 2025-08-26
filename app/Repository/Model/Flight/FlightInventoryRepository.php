<?php

namespace App\Repository\Model\Flight;

use App\Exceptions\CannotDeleteException;
use App\Models\Flight\FlightInventory;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Location\Currency;
use App\Models\Quote\Component\QuoteFlight;
use App\Models\Quote\Quote;
use App\Models\Tour\Event;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\ComponentPackageRepository;
use App\Repository\Abstracts\InventoryRepository;
use App\Repository\Interfaces\Manifest\HasFlightManifest;
use App\Repository\Model\Quote\Component\QuoteFlightRepository;
use App\Repository\Reporting\Manifest\FlightManifestRepository;
use App\Repository\Storage\Itinerary\ItineraryItem;
use App\Repository\Traits\Component\IsFlight;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;
use Settings;

class FlightInventoryRepository extends InventoryRepository implements HasFlightManifest
{
    use IsFlight;

    private FlightInventory $inventory;

    public function __construct(FlightInventory $inventory)
    {
        $this->inventory = $inventory;
    }

    /**
     * @param Carbon $from
     * @param Carbon $to
     * @param ComponentPackageRepository|null $repository
     * @return Collection<FlightInventory>
     */
    public static function getBetweenDates(Carbon $from, Carbon $to, ComponentPackageRepository|null $repository = null): Collection
    {
        $from->setTime(0, 0);
        $to->setTime(23, 59, 59);
        $inventories = [];
        if (isset($repository)) {
            foreach ($repository->getComponents(false, false, true, false, false) as $inventoryTour) {
                $inventories[] = $inventoryTour->getInventory()->get()->id;
            }
        }
        return FlightInventory::whereBetween('departs_at', [$from, $to])->whereBetween('arrives_at', [$from, $to])->whereNotIn('id', $inventories)->get();
    }

    public function get(): FlightInventory
    {
        return $this->inventory;
    }

    public function getStartTime(): Carbon
    {
        return $this->inventory->departs_at;
    }

    public function getEndTime(): Carbon
    {
        return $this->inventory->arrives_at;
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
        $query = DB::table('order_flights');
        $query->join('flight_inventory_tours', 'order_flights.flight_inventory_tour_id', '=', 'flight_inventory_tours.id');
        $query->join('flight_inventories', 'flight_inventory_tours.flight_inventory_id', '=', 'flight_inventories.id');
        $query->join('order_customers', 'order_flights.order_customer_id', '=', 'order_customers.id');
        $query->join('orders', 'order_customers.order_id', '=', 'orders.id');
        $query->where('flight_inventories.id', '=', $this->inventory->id);
        $query->where('orders.cancelled', '=', 0);
        $query->whereNull('order_flights.deleted_at');
        return $query->selectRaw("count(order_flights.id) as 'used_stock'")->first()->used_stock;
    }

    public function update(array $data): FlightInventory
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
        return "{$this->inventory->component} - {$this->inventory->flight_number} ({$this->inventory->travelClass}) (" . f_datetime($this->inventory->departs_at) . " to " . f_datetime($this->inventory->arrives_at) . ")";
    }

    public function addToTour(Tour $tour, string $tourComponentType, float $price = -1): ?FlightInventoryTourRepository
    {
        $inventoryTour = FlightInventoryTour::make([
            'tour_sales_price' => $price == -1 ? $this->inventory->sales_price : $price,
            'tour_component_type' => $tourComponentType,
            'tour_id' => $tour->id,
            'stock_control_active' => $tour->flight_stock_control,
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

    public function addToQuote(Quote $quote, string $tourComponentType, float $price = -1): ?QuoteFlightRepository
    {
        $inventoryTour = QuoteFlight::make([
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
        return $this->inventory->component->currency ?? Settings::currency();
    }

    public function getSalesPrice(): ?float
    {
        return $this->inventory->sales_price;
    }

    public function getFlightManifest(): Collection|array
    {
        return $this->inventory->orders()->with(FlightManifestRepository::getRelations())->get();
    }

    public function isStockControlActive(): bool
    {
        return false;
    }

    public function hasEnoughStock(int $amount = 1): bool
    {
        return true;
    }

    public static function find($id): FlightInventory|null
    {
        return FlightInventory::find($id);
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
            'Flight Number' => $this->inventory->flight_number,
            'Class' => $this->inventory->travelClass->name,
            'Departure Airport' => $this->inventory->component->departureAirport->name,
            'Departure Date' => $this->inventory->departs_at->format('d M Y'),
            'Departure Time' => $this->inventory->departs_at->format('H:i'),
            'Arrival Airport' => $this->inventory->component->arrivalAirport->name,
            'Arrival Date' => $this->inventory->arrives_at->format('d M Y'),
            'Arrival Time' => $this->inventory->arrives_at->format('H:i'),
            'Check In' => f_datetime($this->inventory->check_in),
            'Quantity' => $quantity,
            'Booking Reference' => $this->inventory->flight_number,
            'Description' => $this->inventory->external_notes,
        ];
        if ($quantity === null) { unset($details['Quantity']); }
        return new ItineraryItem(
            $this->inventory->component->airline->name,
            'Flight',
            $this->inventory->departs_at->unix(),
            $details,
        );
    }

    /**
     * Get all inventory that match this one (excluding dates)
     *
     * @return Collection<FlightInventory>|FlightInventory[]
     */
    private function getMatchingInventory(): Collection|array
    {
        return $this->inventory->flight->inventory()
            ->where('travel_class_id', '=', $this->inventory->travel_class_id)
            ->with(['travelClass',])
            ->get();
    }

    public function getStartingAt(Carbon $start): FlightInventory
    {
        $end = $start->copy()->addDays(diff_in_nights($this->inventory->departs_at, $this->inventory->arrives_at));
        foreach ($this->getMatchingInventory() as $inventory) {
            if ($inventory->departs_at->isSameDay($start)
                && $inventory->arrives_at->isSameDay($end)) {
                return $inventory;
            }
        }
        $duplicate = $this->inventory->replicate();
        $duplicate->check_in = $this->inventory->check_in->addDays($this->inventory->departs_at->diffInDays($start));
        $duplicate->departs_at = $start;
        $duplicate->arrives_at = $end;
        $duplicate->save();
        return $duplicate;
    }
}
