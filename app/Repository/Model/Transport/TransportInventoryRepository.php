<?php

namespace App\Repository\Model\Transport;

use App\Models\Quote\Component\QuoteTransport;
use App\Models\Quote\Quote;
use App\Models\Tour\Tour;
use App\Models\Transport\TransportInventory;
use App\Models\Transport\TransportInventoryTour;
use App\Repository\Abstracts\ComponentPackageRepository;
use App\Repository\Abstracts\InventoryRepository;
use App\Repository\Interfaces\Manifest\HasTransportManifest;
use App\Repository\Model\Quote\Component\QuoteTransportRepository;
use App\Repository\Reporting\Manifest\TransportManifestRepository;
use App\Repository\Storage\Order\ItineraryItem;
use App\Repository\Traits\Component\IsTransport;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;
use Settings;

class TransportInventoryRepository extends InventoryRepository implements HasTransportManifest
{
    use IsTransport;

    private TransportInventory $inventory;

    public function __construct(TransportInventory $inventory)
    {
        $this->inventory = $inventory;
    }

    public static function getBetweenDates(Carbon $from, Carbon $to, ComponentPackageRepository $repository = null): Collection
    {
        $from->setTime(0, 0);
        $to->setTime(23, 59, 59);
        $inventories = [];
        if (isset($repository)) {
            foreach ($repository->getComponents(false, false, false, true, false) as $inventoryTour) {
                $inventories[] = $inventoryTour->getInventory()->get()->id;
            }
        }
        return TransportInventory::whereBetween('departs_at', [$from, $to])->whereBetween('arrives_at', [$from, $to])->whereNotIn('id', $inventories)->get();
    }

    public function get(): TransportInventory
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
        $query = DB::table('order_transports');
        $query->join('transport_inventory_tours', 'order_transports.transport_inventory_tour_id', '=', 'transport_inventory_tours.id');
        $query->join('transport_inventories', 'transport_inventory_tours.transport_inventory_id', '=', 'transport_inventories.id');
        $query->join('order_customers', 'order_transports.order_customer_id', '=', 'order_customers.id');
        $query->join('orders', 'order_customers.order_id', '=', 'orders.id');
        $query->where('transport_inventories.id', '=', $this->inventory->id);
        $query->where('orders.cancelled', '=', 0);
        $query->whereNull('order_transports.deleted_at');
        return $query->selectRaw("count(order_transports.id) as 'used_stock'")->first()->used_stock;
    }

    public function update(array $data): TransportInventory
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
        return "{$this->inventory->component} - {$this->inventory->travelClass} (" . f_datetime($this->inventory->departs_at) . " to " . f_datetime($this->inventory->arrives_at) . ")";
    }

    public function addToTour(Tour $tour, string $tourComponentType, float $price = -1): ?TransportInventoryTourRepository
    {
        $inventoryTour = TransportInventoryTour::make([
            'tour_sales_price' => $price == -1 ? $this->inventory->sales_price : $price,
            'tour_component_type' => $tourComponentType,
            'tour_id' => $tour->id,
            'stock_control_active' => $tour->transport_stock_control,
        ]);
        $this->inventory->tourComponents()->save($inventoryTour);
        return $inventoryTour->repository;
    }

    public function addToQuote(Quote $quote, string $tourComponentType, float $price = -1): ?QuoteTransportRepository
    {
        $inventoryTour = QuoteTransport::make([
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

    public function isStockControlActive(): bool
    {
        return false;
    }

    public function hasEnoughStock(int $amount = 1): bool
    {
        return true;
    }

    public function getSalesPrice(): ?float
    {
        return $this->inventory->sales_price;
    }

    public function getTransportManifest(): Collection|array
    {
        return $this->inventory->orders()->with(TransportManifestRepository::getRelations())->get();
    }

    public static function find($id): TransportInventory|null
    {
        return TransportInventory::find($id);
    }

    public function getLocalPurchasePrice(): ?float
    {
        return Settings::convertCurrency($this->getPurchasePrice(), $this->inventory->component->currency) ?? $this->getPurchasePrice();
    }

    public function getPurchasePriceString(): string
    {
        return f_currency($this->getPurchasePrice(), $this->inventory->component->currency);
    }

    public function getItineraryItem(): ItineraryItem
    {
        return new ItineraryItem(
            'Journey',
            null,
            $this->inventory->departs_at,
            $this->inventory->arrives_at,
            [
                'Details' => $this->inventory->component->name,
                'Transport' => $this->inventory->component->transportType->name,
                'Travel Class' => $this->inventory->travelClass->name,
            ]
        );
    }
}
