<?php

namespace App\Repository\Model\Merchandise;

use App\Models\Merchandise\Merchandise;
use App\Models\Merchandise\MerchandiseInventory;
use App\Models\Merchandise\MerchandiseInventoryTour;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\InventoryRepository;
use App\Repository\Abstracts\InventoryTourRepository;
use Carbon\Carbon;
use DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class MerchandiseInventoryRepository extends InventoryRepository
{
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

    public static function getBetweenDates(Carbon $from, Carbon $to, Tour $tour = null): Collection
    {
        return Merchandise::all();
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
            $data['image_url'] = store_file($image, $this->inventory->image_url);
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

    public function addToTour(Tour $tour, string $type): ?InventoryTourRepository
    {
        $mInvTour = MerchandiseInventoryTour::make([
            'merchandise_inventory_id' => $this->inventory->id,
            'tour_component_type' => $type,
            'tour_sales_price' => $this->inventory->sales_price,
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
}
