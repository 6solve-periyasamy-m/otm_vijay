<?php

namespace App\Models\Accommodation;

use App\Models\Helper\Model;
use App\Models\Order\Component\OrderAccommodation;
use App\Models\Supplier\SupplierContractComponent;
use App\Repository\Model\Accommodation\AccommodationInventoryRepository;
use Database\Factories\Accommodation\AccommodationInventoryFactory;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

/**
 * App\Models\Accommodation\AccommodationInventory
 *
 * @property int $id
 * @property int $accommodation_id
 * @property int $room_type_id
 * @property int $board_type_id
 * @property int|null $room_category_id
 * @property int|null $stock_parent_id
 * @property Carbon|null $check_in
 * @property bool $check_in_time_confirmed
 * @property Carbon|null $check_out
 * @property bool $check_out_time_confirmed
 * @property bool $fit_selectable
 * @property int $stock The available stock for this inventory
 * @property float|null $purchase_price
 * @property float|null $sales_price
 * @property string|null $internal_notes
 * @property string|null $external_notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read int $contracted Amount of contracted stock
 * @property-read float $local_purchase_price FX Converted Purchase Price
 * @property-read Accommodation $accommodation
 * @property-read BoardType $boardType
 * @property-read RoomCategory $category
 * @property-read Accommodation $component
 * @property-read AccommodationInventory|null $stockParent
 * @property-read AccommodationInventory[] $stockChildren
 * @property-read string $accommodation_for_tour
 * @property-read string $customer_display Display string to show to customers
 * @property-read int $used_on_tour_count How many tours this inventory is used on
 * @property-read int $used_stock The amount of stock that has been sold
 * @property-read int $available_stock The amount of stock that is available to be sold
 * @property-read int $total_stock The total stock available to sell (either for component or parent)
 * @property-read RoomType $roomType
 * @property-read Collection|SupplierContractComponent[] $contractComponents
 * @property-read Collection|AccommodationInventoryTour[] $tourComponents
 * @property-read Collection|OrderAccommodation[] $orderComponents
 * @property-read int|null $tour_components_count
 * @property-read AccommodationInventoryRepository $repository
 * @method static AccommodationInventoryFactory factory(...$parameters)
 * @method static Builder|AccommodationInventory newModelQuery()
 * @method static Builder|AccommodationInventory newQuery()
 * @method static QueryBuilder|AccommodationInventory onlyTrashed()
 * @method static Builder|AccommodationInventory query()
 * @method static Builder|AccommodationInventory whereAccommodationId($value)
 * @method static Builder|AccommodationInventory whereBoardTypeId($value)
 * @method static Builder|AccommodationInventory whereCheckIn($value)
 * @method static Builder|AccommodationInventory whereCheckInTimeConfirmed($value)
 * @method static Builder|AccommodationInventory whereCheckOut($value)
 * @method static Builder|AccommodationInventory whereCheckOutTimeConfirmed($value)
 * @method static Builder|AccommodationInventory whereCreatedAt($value)
 * @method static Builder|AccommodationInventory whereDeletedAt($value)
 * @method static Builder|AccommodationInventory whereFitSelectable($value)
 * @method static Builder|AccommodationInventory whereId($value)
 * @method static Builder|AccommodationInventory whereNotes($value)
 * @method static Builder|AccommodationInventory wherePurchasePrice($value)
 * @method static Builder|AccommodationInventory whereRoomTypeId($value)
 * @method static Builder|AccommodationInventory whereSalesPrice($value)
 * @method static Builder|AccommodationInventory whereStock($value)
 * @method static Builder|AccommodationInventory whereUpdatedAt($value)
 * @method static QueryBuilder|AccommodationInventory withTrashed()
 * @method static QueryBuilder|AccommodationInventory withoutTrashed()
 * @mixin Eloquent
 */
class AccommodationInventory extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasRelationships;

    protected $guarded = [];
    protected array $cascadeDeletes = ['tourComponents'];
    protected $casts = [
        'check_in' => 'datetime:Y-m-d H:i:s',
        'check_out' => 'datetime:Y-m-d H:i:s',
        'check_in_time_confirmed' => 'boolean',
        'check_out_time_confirmed' => 'boolean',
        'fit_selectable' => 'boolean',
        'purchase_price' => 'double',
        'sales_price' => 'double',
    ];
    private AccommodationInventoryRepository $internal_repository;

    public static function findByTour($tour_id): Collection|array
    {
        return AccommodationInventory::with(['tour' => function ($q) use ($tour_id) {
            $q->where('tour_id', $tour_id);
        }])->with('component_type')->get();
    }

    public function accommodation(): BelongsTo
    {
        return $this->belongsTo(Accommodation::class, 'accommodation_id');
    }

    public function stockParent(): BelongsTo
    {
        return $this->belongsTo(AccommodationInventory::class, 'stock_parent_id');
    }

    public function stockChildren(): HasMany
    {
        return $this->hasMany(AccommodationInventory::class, 'stock_parent_id');
    }

    public function component(): BelongsTo
    {
        return $this->belongsTo(Accommodation::class, 'accommodation_id');
    }

    public function contractComponents(): MorphMany
    {
        return $this->morphMany(SupplierContractComponent::class, 'component');
    }

    public function boardType(): BelongsTo
    {
        return $this->belongsTo(BoardType::class);
    }

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(RoomCategory::class, 'room_category_id');
    }

    public function getAccommodationForTourAttribute(): string
    {
        $check_in = !is_null($this->check_in) ? $this->check_in->format('d/m/Y H:i') : "Unconfirmed";
        $check_out = !is_null($this->check_out) ? $this->check_out->format('d/m/Y H:i') : "Unconfirmed";

        return "{$this->accommodation->name} - {$this->accommodation->region->name}｜Check in: {$check_in} - Check out: {$check_out}｜Room Type: {$this->roomType->name} - Board Type: {$this->boardType->name}";
    }

    public function getTotalStockAttribute(): int
    {
        return $this->repository->getTotalStock();
    }

    public function getUsedStockAttribute(): int
    {
        return $this->repository->getUsedStock();
    }

    public function getAvailableStockAttribute(): int
    {
        return $this->repository->getAvailableStock();
    }

    public function getUsedOnTourCountAttribute(): int
    {
        return $this->tourComponents()->count();
    }

    public function tourComponents(): HasMany
    {
        return $this->hasMany(AccommodationInventoryTour::class, 'accommodation_inventory_id');
    }

    public function orderComponents(): HasManyThrough
    {
        return $this->hasManyThrough(OrderAccommodation::class, AccommodationInventoryTour::class, 'accommodation_inventory_id', 'accommodation_inventory_tour_id');
    }

    public function getContractedAttribute(): int
    {
        return $this->contractComponents()->sum('quantity');
    }

    public function __toString(): string
    {
        return $this->repository->__toString();
    }

    public function getCustomerDisplayAttribute(): string
    {
        return "{$this->component} - {$this->roomType->name} {$this->boardType} (" . f_datetime($this->check_in) . " to " . f_datetime($this->check_out) . ")";
    }

    public function getRepositoryAttribute(): AccommodationInventoryRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new AccommodationInventoryRepository($this);
        return $this->internal_repository;
    }

    public function getLocalPurchasePriceAttribute(): float
    {
        return fx_convert($this->purchase_price, $this->component->currency);
    }
}
