<?php

namespace App\Models\Order\Component;

use App\Models\Merchandise\MerchandiseInventoryTour;
use App\Models\Order\OrderCustomer;
use App\Repository\Model\Order\Component\OrderMerchandiseRepository;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Order\Component\OrderMerchandise
 *
 * @property int $id
 * @property int $order_customer_id
 * @property int $merchandise_inventory_tour_id
 * @property float $cost
 * @property boolean $fulfilled
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property float|null $estimated_purchase_price
 * @property-read float $purchase_price
 * @property-read bool $cancelled Is the order cancelled?
 * @property-read string $details
 * @property-read string $tour_component_type
 * @property-read float $tour_sales_price
 * @property-read MerchandiseInventoryTour $merchandise
 * @property-read OrderCustomer $orderCustomer
 * @property-read MerchandiseInventoryTour $tourComponent
 * @property-read OrderMerchandiseRepository $repository The repository used for calculations and storage
 * @method static Builder|OrderMerchandise newModelQuery()
 * @method static Builder|OrderMerchandise newQuery()
 * @method static QueryBuilder|OrderMerchandise onlyTrashed()
 * @method static Builder|OrderMerchandise query()
 * @method static Builder|OrderMerchandise whereCost($value)
 * @method static Builder|OrderMerchandise whereCreatedAt($value)
 * @method static Builder|OrderMerchandise whereDeletedAt($value)
 * @method static Builder|OrderMerchandise whereId($value)
 * @method static Builder|OrderMerchandise whereMerchandiseInventoryTourId($value)
 * @method static Builder|OrderMerchandise whereOrderCustomerId($value)
 * @method static Builder|OrderMerchandise whereUpdatedAt($value)
 * @method static QueryBuilder|OrderMerchandise withTrashed()
 * @method static QueryBuilder|OrderMerchandise withoutTrashed()
 * @mixin Eloquent
 */
class OrderMerchandise extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $casts = ['cost' => 'double', 'fulfilled' => 'boolean'];

    private OrderMerchandiseRepository $internal_repository;

    public function orderCustomer(): BelongsTo
    {
        return $this->belongsTo(OrderCustomer::class, 'order_customer_id');
    }

    public function merchandise(): BelongsTo
    {
        return $this->belongsTo(MerchandiseInventoryTour::class, 'merchandise_inventory_tour_id');
    }

    public function tourComponent(): BelongsTo
    {
        return $this->belongsTo(MerchandiseInventoryTour::class, 'merchandise_inventory_tour_id');
    }

    public function getCancelledAttribute(): bool
    {
        // If the order customer or order don't exist, assume cancelled
        return $this->orderCustomer?->order?->cancelled ?? true;
    }

    public function getDetailsAttribute(): string
    {
        return "{$this->tourComponent}";
    }

    public function getTourComponentTypeAttribute(): string
    {
        return $this->tourComponent->tour_component_type;
    }

    public function getTourSalesPriceAttribute(): float
    {
        return $this->tourComponent->tour_sales_price ?? 0.0;
    }

    public function getRepositoryAttribute(): OrderMerchandiseRepository
    {
        if (!isset ($this->internal_repository)) $this->internal_repository = new OrderMerchandiseRepository($this);
        return $this->internal_repository;
    }

    public function getPurchasePriceAttribute(): float
    {
        $inventory = $this->tourComponent?->inventory;
        return $this->estimated_purchase_price ?? $inventory?->local_purchase_price ?? 0.0;
    }
}
