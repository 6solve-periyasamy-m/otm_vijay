<?php

namespace App\Repository\Model\Merchandise;

use App\Models\Merchandise\Merchandise;
use App\Models\Merchandise\MerchandiseInventory;
use App\Models\Merchandise\MerchandiseInventoryTour;
use App\Models\Quote\Component\QuoteMerchandise;
use App\Models\Quote\Quote;
use App\Models\Tour\Tour;
use App\Models\Transport\TransportInventory;
use App\Repository\Abstracts\ComponentPackageRepository;
use App\Repository\Abstracts\InventoryRepository;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Model\Quote\Component\QuoteMerchandiseRepository;
use App\Repository\Storage\Itinerary\ItineraryItem;
use App\Repository\Traits\Component\IsMerchandise;
use Carbon\Carbon;
use DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class MerchandiseInventoryRepository extends InventoryRepository
{
    use IsMerchandise;

    private MerchandiseInventory $inventory;

    public function __construct(MerchandiseInventory $inventory)
    {
        $this->inventory = $inventory;
    }

    public static function create(Merchandise $merchandise, array $data, ?UploadedFile $image = null): MerchandiseInventory
    {
        if ($image !== null) {
            $data['image_url'] = store_file($image);
        }
        $inventory = MerchandiseInventory::make($data);
        $merchandise->inventory()->save($inventory);
        return $inventory;
    }

    /**
     * @return Collection|MerchandiseInventory[]
     */
    public function getSizeVariants(): Collection|array
    {
        return MerchandiseInventory::whereNot('id', '=', $this->inventory->id)->where('variant_id', '=', $this->inventory->variant_id)->where('merchandise_id', '=', $this->inventory->merchandise_id)->get();
    }

    /**
     * @inheritDoc
     */
    public function getUsedStock(): int
    {
        $query = DB::table('order_merchandises');
        $query->join('merchandise_inventory_tours', 'order_merchandises.merchandise_inventory_tour_id', '=', 'merchandise_inventory_tours.id');
        $query->join('merchandise_inventories', 'merchandise_inventory_tours.merchandise_inventory_id', '=', 'merchandise_inventories.id');
        $query->join('order_customers', 'order_merchandises.order_customer_id', '=', 'order_customers.id');
        $query->join('orders', 'order_customers.order_id', '=', 'orders.id');
        $query->where('merchandise_inventories.id', '=', $this->inventory->id);
        $query->where('orders.cancelled', '=', 0);
        $query->whereNull('order_merchandises.deleted_at');
        return $query->selectRaw("count(order_merchandises.id) as 'used_stock'")->first()->used_stock;
    }

    /**
     * @inheritDoc
     */
    public function getTotalStock(): int
    {
        return $this->inventory->stock;
    }

    /**
     * @inheritDoc
     */
    public function getAvailableStock(): int
    {
        return $this->getTotalStock() - $this->getUsedStock();
    }

    public function getStartTime(): Carbon
    {
        return now();
    }

    public function getEndTime(): Carbon
    {
        return now();
    }

    /**
     * @param Carbon $from
     * @param Carbon $to
     * @param ComponentPackageRepository|null $repository
     * @return Collection<MerchandiseInventory>
     */
    public static function getBetweenDates(Carbon $from, Carbon $to, ComponentPackageRepository $repository = null): Collection
    {
        $inventories = [];
        if (isset($repository)) {
            foreach ($repository->getComponents(false, false, false, false, true) as $inventoryTour) {
                $inventories[] = $inventoryTour->getInventory()?->get()->id;
            }
        }
        return MerchandiseInventory::whereNotIn('id', $inventories)->get();

    }

    public function get(): MerchandiseInventory
    {
        return $this->inventory;
    }

    public function update(array $data): MerchandiseInventory
    {
        $this->inventory->update($data);
        $this->save();
        return $this->get();
    }

    public function updateWithImage(array $data, ?UploadedFile $image = null): MerchandiseInventory
    {
        if ($image !== null) {
            $data['image_url'] = store_file($image);
        }
        return $this->update($data);
    }

    public function save(): bool
    {
        return $this->inventory->save();
    }

    public function delete(): bool
    {
        if ($this->inventory->tourComponents()->count() > 0) return false;
        return $this->inventory->delete();
    }

    public function isDeleted(): bool
    {
        return $this->inventory->trashed();
    }

    public function __toString(): string
    {
        return "{$this->inventory->component->name}" . (isset($this->inventory->variant) ? " ({$this->inventory->variant->name})" : "");
    }

    public function addToTour(Tour $tour, string $tourComponentType, float $price = -1): ?InventoryTourRepository
    {
        $mInvTour = MerchandiseInventoryTour::where('tour_id', '=', $tour->id)->where('merchandise_inventory_id', '=', $this->inventory->id)->first();
        if (isset($mInvTour)) return $mInvTour->repository;
        $mInvTour = MerchandiseInventoryTour::make([
            'merchandise_inventory_id' => $this->inventory->id,
            'tour_component_type' => $tourComponentType,
            'tour_sales_price' => $price == -1 ? $this->inventory->sales_price : $price,
            'stock_control_active' => $tour->merchandise_stock_control,
        ]);
        $tour->merchandise()->save($mInvTour);
        return $mInvTour->repository;
    }

    public function getOrderCount(): int
    {
        $count = 0;
        foreach ($this->inventory->tourComponents()->withCount('orderComponents')->get() as $tourComponent) {
            $count += $tourComponent->orderComponents()->count();
        }
        return $count;
    }

    public function addToQuote(Quote $quote, string $tourComponentType, float $price = -1): ?QuoteMerchandiseRepository
    {
        $inventoryTour = QuoteMerchandise::make([
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

    public static function find($id): MerchandiseInventory|null
    {
        return MerchandiseInventory::find($id);
    }

    public function getLocalPurchasePrice(): ?float
    {
        return $this->getPurchasePrice() ?? 0.0;
    }

    public function getPurchasePriceString(): string
    {
        return f_currency($this->getPurchasePrice());
    }

    public function getItineraryItem(int|null $quantity = null): ItineraryItem
    {
        $details = [
            'Type' => $this->inventory->component->type?->name,
            'Size' => $this->inventory->size?->name,
            'Variant' => $this->inventory->variant?->name,
            'Quantity' => $quantity,
        ];
        return new ItineraryItem(
            $this->inventory->component->name,
            'Merchandise',
            $details
        );
    }
}
