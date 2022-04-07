<?php

namespace App\Models\Accommodation;

use App\Models\BoardType;
use App\Models\RoomType;
use App\Repository\StockRepository;
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
 * App\Models\Accommodation\AccommodationInventory
 *
 * @property int $id
 * @property int $accommodation_id
 * @property int $room_type_id
 * @property int $board_type_id
 * @property Carbon|null $check_in
 * @property bool $check_in_time_confirmed
 * @property Carbon|null $check_out
 * @property bool $check_out_time_confirmed
 * @property bool $fit_selectable
 * @property int $stock The available stock for this inventory
 * @property float|null $purchase_price
 * @property float|null $sales_price
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Accommodation $accommodation
 * @property-read BoardType $boardType
 * @property-read Accommodation $component
 * @property-read string $accommodation_for_tour
 * @property-read string $customer_display Display string to show to customers
 * @property-read int $used_on_tour_count How many tours this inventory is used on
 * @property-read int $used_stock The amount of stock that has been sold
 * @property-read RoomType $roomType
 * @property-read Collection|AccommodationInventoryTour[] $tourComponents
 * @property-read int|null $tour_components_count
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
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $fillable = ['accommodation_id', 'room_type_id', 'board_type_id', 'check_in', 'check_in_time_confirmed', 'check_out', 'check_out_time_confirmed', 'fit_selectable', 'stock', 'purchase_price', 'sales_price', 'notes', 'currency_id'];
    protected array $cascadeDeletes = ['tourComponents'];
    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    public static function getValidationRules(): array
    {
        return [
            'room_type_id' => 'required|exists:room_types,id',
            'board_type_id' => 'required|exists:board_types,id',
            'check_in' => 'date',
            'check_out' => 'date',
            'stock' => 'required|numeric|integer',
            'purchase_price' => 'required|numeric',
            'sales_price' => 'required|numeric',
        ];
    }

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

    public function component(): BelongsTo
    {
        return $this->belongsTo(Accommodation::class, 'accommodation_id');
    }

    public function boardType(): BelongsTo
    {
        return $this->belongsTo(BoardType::class);
    }

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    public function tourComponents(): HasMany
    {
        return $this->hasMany(AccommodationInventoryTour::class, 'accommodation_inventory_id');
    }

    public function getAccommodationForTourAttribute(): string
    {
        $check_in = !is_null($this->check_in) ? $this->check_in->format('d/m/Y H:i') : "Unconfirmed";
        $check_out = !is_null($this->check_out) ? $this->check_out->format('d/m/Y H:i') : "Unconfirmed";

        return "{$this->accommodation->name} - {$this->accommodation->region->name}｜Check in: {$check_in} - Check out: {$check_out}｜Room Type: {$this->roomType->name} - Board Type: {$this->boardType->name}";
    }

    public function getUsedStockAttribute(): int
    {
        return StockRepository::getAccommodationStock($this);
    }

    public function getUsedOnTourCountAttribute(): int
    {
        return $this->tourComponents()->count();
    }

    public function __toString(): string
    {
        return "{$this->component} - {$this->roomType} {$this->boardType} (" . \StringFormatter::formatDateTime($this->check_in) . " to " . \StringFormatter::formatDateTime($this->check_out) . ")";
    }

    public function getCustomerDisplayAttribute(): string
    {
        return "{$this->component} - {$this->roomType->name} {$this->boardType} (" . \StringFormatter::formatDateTime($this->check_in) . " to " . \StringFormatter::formatDateTime($this->check_out) . ")";
    }
}
