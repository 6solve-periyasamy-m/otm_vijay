<?php

namespace App\Models\Accommodation;

use App\Models\BoardType;
use App\Models\RoomType;
use App\Repository\StockRepository;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccommodationInventory extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $fillable = ['accommodation_id', 'room_type_id', 'board_type_id', 'check_in', 'check_in_time_confirmed', 'check_out', 'check_out_time_confirmed', 'fit_selectable', 'stock', 'purchase_price', 'sales_price', 'notes', 'currency_id'];
    protected $cascadeDeletes = ['tourComponents'];
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
