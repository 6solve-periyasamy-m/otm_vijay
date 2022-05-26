<?php

namespace App\Models\Activity;

use App\Repository\StockRepository;
use Database\Factories\Activity\ActivityInventoryFactory;
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
use StringFormatter;


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
 * @property float $purchase_price
 * @property float $sales_price
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Activity $activity
 * @property-read Activity $component
 * @property-read string $activity_for_tour
 * @property-read int $used_on_tour_count
 * @property-read int $used_stock How much stock is sold
 * @property-read TicketType $ticketType
 * @property-read Collection|ActivityInventoryTour[] $tourComponents
 * @property-read int|null $tour_components_count
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

    protected $fillable = ['activity_id', 'ticket_type_id', 'starts_at', 'ends_at', 'fit_selectable', 'stock', 'purchase_price', 'sales_price', 'currency_id', 'notes',];
    protected array $cascadeDeletes = ['tourComponents'];
    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'fit_selectable' => 'boolean',
        'purchase_price' => 'double',
        'sales_price' => 'double',
    ];

    public static function getValidationRules(): array
    {
        return [
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'starts_at' => 'date',
            'ends_at' => 'date',
            'stock' => 'required|numeric|integer',
            'purchase_price' => 'required|numeric',
            'sales_price' => 'required|numeric',
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

    public function ticketType(): BelongsTo
    {
        return $this->belongsTo(TicketType::class, 'ticket_type_id');
    }

    public function tourComponents(): HasMany
    {
        return $this->hasMany(ActivityInventoryTour::class, 'activity_inventory_id');
    }

    public function getActivityForTourAttribute(): string
    {
        $starts_at = $this->starts_at->format('d/m/Y H:i');
        $ends_at = $this->ends_at->format('d/m/Y H:i');

        return "{$this->activity->name}｜Activity Start: {$starts_at}｜Activity End: {$ends_at}｜Ticket Type: {$this->ticketType->name}";
    }

    public function getUsedStockAttribute(): int
    {
        return StockRepository::getActivityStock($this);
    }

    public function getUsedOnTourCountAttribute(): int
    {
        return $this->tourComponents()->count();
    }

    public function __toString(): string
    {
        return "{$this->component} - {$this->ticketType} (" . f_datetime($this->starts_at) . " to " . f_datetime($this->ends_at) . ")";
    }
}
