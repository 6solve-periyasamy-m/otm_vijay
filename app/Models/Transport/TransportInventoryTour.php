<?php

namespace App\Models\Transport;

use App\Models\Booking\Component\BookingTransport;
use App\Models\Order\Component\OrderTransport;
use App\Models\Order\OrderCustomer;
use App\Models\Tour\Tour;
use App\Repository\Model\Transport\TransportInventoryTourRepository;
use Database\Factories\Transport\TransportInventoryTourFactory;
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
use Illuminate\Validation\Rule;

/**
 * App\Models\Transport\TransportInventoryTour
 *
 * @property int $id
 * @property int $tour_id
 * @property int $transport_inventory_id
 * @property string $tour_component_type
 * @property float $tour_sales_price
 * @property bool $stock_control_active
 * @property bool $is_bookable
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read int $available_stock
 * @property-read string $tour_name
 * @property-read int $used_tour_stock
 * @property-read TransportInventory $inventory
 * @property-read Collection|OrderTransport[] $orders
 * @property-read int|null $orders_count
 * @property-read Tour $tour
 * @property-read TransportInventory $transportInventory
 * @property-read Collection|TransportInventoryTourUpgrade[] $upgradeParents
 * @property-read int|null $upgrade_parents_count
 * @property-read Collection|TransportInventoryTourUpgrade[] $upgrades
 * @property-read int|null $upgrades_count
 * @property-read TransportInventoryTourRepository $repository
 * @method static TransportInventoryTourFactory factory(...$parameters)
 * @method static Builder|TransportInventoryTour newModelQuery()
 * @method static Builder|TransportInventoryTour newQuery()
 * @method static QueryBuilder|TransportInventoryTour onlyTrashed()
 * @method static Builder|TransportInventoryTour query()
 * @method static Builder|TransportInventoryTour whereCreatedAt($value)
 * @method static Builder|TransportInventoryTour whereDeletedAt($value)
 * @method static Builder|TransportInventoryTour whereId($value)
 * @method static Builder|TransportInventoryTour whereTourComponentType($value)
 * @method static Builder|TransportInventoryTour whereTourId($value)
 * @method static Builder|TransportInventoryTour whereIsBookable($value)
 * @method static Builder|TransportInventoryTour whereStockControlActive($value)
 * @method static Builder|TransportInventoryTour whereTourSalesPrice($value)
 * @method static Builder|TransportInventoryTour whereTransportInventoryId($value)
 * @method static Builder|TransportInventoryTour whereUpdatedAt($value)
 * @method static QueryBuilder|TransportInventoryTour withTrashed()
 * @method static QueryBuilder|TransportInventoryTour withoutTrashed()
 * @mixin Eloquent
 */
class TransportInventoryTour extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $guarded = [];
    protected array $cascadeDeletes = ['orders', 'upgrades', 'upgradeParents'];
    protected $casts = ['tour_sales_price' => 'double', 'is_bookable' => 'boolean','stock_control_active' => 'boolean',];

    private TransportInventoryTourRepository $internal_repository;

    public static function getValidationRules(): array
    {
        return [
            'tour_component_type' => [
                'required',
                Rule::in(['Included', 'Upgrade', 'Add-on'])
            ],
            'tour_sales_price' => 'required|numeric',
            'transport_inventory_id' => 'required|exists:transport_inventories,id'
        ];
    }

    public function transportInventory(): BelongsTo
    {
        return $this->belongsTo(TransportInventory::class, 'transport_inventory_id');
    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(TransportInventory::class, 'transport_inventory_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(OrderTransport::class, 'transport_inventory_tour_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(BookingTransport::class, 'transport_inventory_tour_id');
    }

    public function upgrades(): HasMany
    {
        return $this->hasMany(TransportInventoryTourUpgrade::class, 'base_id');
    }

    // Only used for Cascading Soft Deletes
    public function upgradeParents(): HasMany
    {
        return $this->hasMany(TransportInventoryTourUpgrade::class, 'upgrade_id');
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function __toString(): string
    {
        return $this->repository->__toString();
    }

    public function getTourNameAttribute(): string
    {
        return $this->tour?->name ?? 'Tour Deleted';
    }

    public function getAvailableStockAttribute(): int
    {
        return $this->inventory->stock - $this->inventory->used_stock;
    }

    public function parent(): TransportInventoryTour
    {
        return $this->repository->getUpgradeParent();
    }

    public function addToOrder(OrderCustomer $orderCustomer): OrderTransport
    {
        return $this->repository->grantToCustomer($orderCustomer)->get();
    }

    public function getUsedTourStockAttribute(): int
    {
        return $this->repository->getUsedOnOrderCount();
    }

    public function getRepositoryAttribute(): TransportInventoryTourRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new TransportInventoryTourRepository($this);
        return $this->internal_repository;
    }
}
