<?php

namespace App\Models\Merchandise;

use App\Models\Booking\Component\BookingMerchandise;
use App\Models\Order\Component\OrderMerchandise;
use App\Repository\Model\Merchandise\MerchandiseInventoryTourRepository;
use Database\Factories\Merchandise\MerchandiseInventoryTourFactory;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Merchandise\MerchandiseInventoryTour
 *
 * @property int $id
 * @property int $merchandise_inventory_id
 * @property int $tour_id
 * @property string $tour_component_type
 * @property float $tour_sales_price
 * @property bool $stock_control_active
 * @property bool $is_bookable
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read int $available_stock
 * @property-read MerchandiseInventoryTourRepository $repository
 * @property-read int $used_stock
 * @property-read MerchandiseInventory $inventory
 * @property-read Collection|OrderMerchandise[] $orderComponents
 * @property-read int|null $order_components_count
 * @method static MerchandiseInventoryTourFactory factory(...$parameters)
 * @method static Builder|MerchandiseInventoryTour newModelQuery()
 * @method static Builder|MerchandiseInventoryTour newQuery()
 * @method static QueryBuilder|MerchandiseInventoryTour onlyTrashed()
 * @method static Builder|MerchandiseInventoryTour query()
 * @method static Builder|MerchandiseInventoryTour whereCreatedAt($value)
 * @method static Builder|MerchandiseInventoryTour whereDeletedAt($value)
 * @method static Builder|MerchandiseInventoryTour whereId($value)
 * @method static Builder|MerchandiseInventoryTour whereIsBookable($value)
 * @method static Builder|MerchandiseInventoryTour whereMerchandiseInventoryId($value)
 * @method static Builder|MerchandiseInventoryTour whereTourComponentType($value)
 * @method static Builder|MerchandiseInventoryTour whereStockControlActive($value)
 * @method static Builder|MerchandiseInventoryTour whereTourId($value)
 * @method static Builder|MerchandiseInventoryTour whereTourSalesPrice($value)
 * @method static Builder|MerchandiseInventoryTour whereUpdatedAt($value)
 * @method static QueryBuilder|MerchandiseInventoryTour withTrashed()
 * @method static QueryBuilder|MerchandiseInventoryTour withoutTrashed()
 * @mixin Eloquent
 */
class MerchandiseInventoryTour extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    private MerchandiseInventoryTourRepository $internal_repository;

    protected $guarded = [];
    protected $casts = [
        'tour_sales_price' => 'double',
        'is_bookable' => 'boolean',
        'stock_control_active' => 'boolean',
    ];

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(MerchandiseInventory::class, 'merchandise_inventory_id');
    }

    public function orderComponents(): HasMany
    {
        return $this->hasMany(OrderMerchandise::class, 'merchandise_inventory_tour_id');
    }

    public function bookingComponents(): HasMany
    {
        return $this->hasMany(BookingMerchandise::class, 'merchandise_inventory_tour_id');
    }

    public function getRepositoryAttribute(): MerchandiseInventoryTourRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new MerchandiseInventoryTourRepository($this);
        return $this->internal_repository;
    }

    public function getUsedStockAttribute(): int
    {
        return $this->repository->getUsedStock();
    }

    public function getAvailableStockAttribute(): int
    {
        return $this->repository->getAvailableStock();
    }

    public function __toString(): string
    {
        return "{$this->inventory}";
    }
}
