<?php

namespace App\Models\Activity;

use App\Models\TicketType;
use App\Repository\StockRepository;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class ActivityInventory extends Model
{
    use SoftDeletes, CascadeSoftDeletes, HasFactory;

    protected $fillable = ['activity_id', 'ticket_type_id', 'starts_at', 'ends_at', 'fit_selectable', 'stock', 'purchase_price', 'sales_price', 'currency_id', 'notes',];
    protected array $cascadeDeletes = ['tourComponents'];
    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
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
        return "{$this->component} - {$this->ticketType} (" . \StringFormatter::formatDateTime($this->starts_at) . " to " . \StringFormatter::formatDateTime($this->ends_at) . ")";
    }
}
