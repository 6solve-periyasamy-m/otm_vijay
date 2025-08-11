<?php

namespace App\Models\Activity;

use App\Models\Order\Component\OrderActivity;
use App\Models\Quote\Component\QuoteActivity;
use App\Models\Supplier\SupplierContractComponent;
use App\Repository\Model\Activity\ActivityInventoryRepository;
use Database\Factories\Activity\ActivityInventoryFactory;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;


/**
 * App\Models\Activity\ActivityInventory
 *
 * @property int $id
 * @property int $activity_id
 * @property Carbon|null $starts_at
 * @property Carbon|null $ends_at
 * @property bool|null $fit_selectable
 * @property int $ticket_type_id
 * @property int $stock
 * @property float|null $purchase_price
 * @property float|null $sales_price
 * @property string|null $internal_notes
 * @property string|null $external_notes
 * @property string|null $description Description used on the booking form/eccommerce page
 * @property string|null $inventory_description Description used on admin dashboard
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read int $contracted Amount of contracted stock
 * @property-read float $local_purchase_price FX Converted Purchase Price
 * @property-read Activity $activity
 * @property-read Activity $component
 * @property-read string $activity_for_tour
 * @property-read int $used_on_tour_count
 * @property-read int $used_stock How much stock is sold
 * @property-read TicketType $ticketType
 * @property-read Collection|SupplierContractComponent[] $contractComponents
 * @property-read Collection|ActivityInventoryTour[] $tourComponents
 * @property-read Collection|OrderActivity[] $orders
 * @property-read int|null $tour_components_count
 * @property-read ActivityInventoryRepository $repository
 * @method static ActivityInventoryFactory factory(...$parameters)
 * @method static Builder|ActivityInventory newModelQuery()
 * @method static Builder|ActivityInventory newQuery()
 * @method static QueryBuilder|ActivityInventory onlyTrashed()
 * @method static Builder|ActivityInventory query()
 * @method static Builder|ActivityInventory whereActivityId($value)
 * @method static Builder|ActivityInventory whereCreatedAt($value)
 * @method static Builder|ActivityInventory whereDeletedAt($value)
 * @method static Builder|ActivityInventory whereEndsAt($value)
 * @method static Builder|ActivityInventory whereFitSelectable($value)
 * @method static Builder|ActivityInventory whereId($value)
 * @method static Builder|ActivityInventory whereNotes($value)
 * @method static Builder|ActivityInventory wherePurchasePrice($value)
 * @method static Builder|ActivityInventory whereSalesPrice($value)
 * @method static Builder|ActivityInventory whereStartsAt($value)
 * @method static Builder|ActivityInventory whereStock($value)
 * @method static Builder|ActivityInventory whereTicketTypeId($value)
 * @method static Builder|ActivityInventory whereUpdatedAt($value)
 * @method static QueryBuilder|ActivityInventory withTrashed()
 * @method static QueryBuilder|ActivityInventory withoutTrashed()
 * @mixin Eloquent
 */
class ActivityInventory extends Model
{
    use SoftDeletes, CascadeSoftDeletes, HasFactory;

    protected $guarded = [];
    protected array $cascadeDeletes = ['tourComponents'];
    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'fit_selectable' => 'boolean',
        'purchase_price' => 'double',
        'sales_price' => 'double',
    ];
    private ActivityInventoryRepository $internal_repository;

    public static function getValidationRules(): array
    {
        return [
            'ticket_type_id' => 'required|exists:ticket_types,id',
            /*'starts_at' => 'date',
            'ends_at' => 'date',*/
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date',
            'stock' => 'required|numeric|integer',
            'purchase_price' => 'nullable|numeric',
            'sales_price' => 'nullable|numeric',
        ];
    }

    public static function findByTour($tour_id): Collection|array
    {
        return ActivityInventory::with(['tour' => function ($q) use ($tour_id) {
            $q->where('tour_id', $tour_id);
        }])->get();
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }

    public function component(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }

    public function contractComponents(): MorphMany
    {
        return $this->morphMany(SupplierContractComponent::class, 'component');
    }

    public function orders(): HasManyThrough
    {
        return $this->hasManyThrough(OrderActivity::class, ActivityInventoryTour::class, 'activity_inventory_id', 'activity_inventory_tour_id');
    }

    public function ticketType(): BelongsTo
    {
        return $this->belongsTo(TicketType::class, 'ticket_type_id');
    }

    public function getActivityForTourAttribute(): string
    {
        $starts_at = $this->starts_at?->format('d/m/Y H:i');
        $ends_at = $this->ends_at?->format('d/m/Y H:i');

        return "{$this->activity->name}｜Activity Start: {$starts_at}｜Activity End: {$ends_at}｜Ticket Type: {$this->ticketType->name}";
    }

    public function getContractedAttribute(): int
    {
        return $this->contractComponents()->sum('quantity');
    }

    public function getUsedStockAttribute(): int
    {
        return $this->repository->getUsedStock();
    }

    public function getUsedOnTourCountAttribute(): int
    {
        return $this->tourComponents()->count();
    }

    public function tourComponents(): HasMany
    {
        return $this->hasMany(ActivityInventoryTour::class, 'activity_inventory_id');
    }

    public function quoteComponents(): HasMany
    {
        return $this->hasMany(QuoteActivity::class, 'activity_inventory_id');
    }

    public function __toString(): string
    {
        $dateString = "";
        if ($this->starts_at !== null && $this->ends_at !== null) {
            $dateString = "(" . f_datetime($this->starts_at) . " to " . f_datetime($this->ends_at) . ")";
        }
        return "{$this->component} - {$this->ticketType} {$dateString}";
    }

    public function getRepositoryAttribute(): ActivityInventoryRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new ActivityInventoryRepository($this);
        return $this->internal_repository;
    }

    public function getLocalPurchasePriceAttribute(): float|null
    {
        return fx_convert($this->purchase_price, $this->component->currency);
    }
}
