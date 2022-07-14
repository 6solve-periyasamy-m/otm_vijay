<?php

namespace App\Repository\Model\Merchandise;

use App\Models\Merchandise\MerchandiseInventory;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\InventoryRepository;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class MerchandiseInventoryRepository extends InventoryRepository
{
    private MerchandiseInventory $inventory;

    public function __construct(MerchandiseInventory $inventory)
    {
        $this->inventory = $inventory;
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
        // TODO: Implement getStartTime() method.
    }

    public function getEndTime(): Carbon
    {
        // TODO: Implement getEndTime() method.
    }

    public static function getBetweenDates(Carbon $from, Carbon $to, Tour $tour = null): Collection
    {
        // TODO: Implement getBetweenDates() method.
    }

    public function get(): Model
    {
        // TODO: Implement get() method.
    }

    public function update(array $data): Model
    {
        // TODO: Implement update() method.
    }

    public function save(): bool
    {
        // TODO: Implement save() method.
    }

    public function delete(): bool
    {
        // TODO: Implement delete() method.
    }

    public function isDeleted(): bool
    {
        // TODO: Implement isDeleted() method.
    }

    public function __toString(): string
    {
        // TODO: Implement __toString() method.
    }
}
