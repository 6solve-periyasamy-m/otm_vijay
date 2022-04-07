<?php

namespace App\Models\Flight;

use App\Models\Airport;
use App\Models\TravelClass;
use App\Repository\StockRepository;
use Carbon\Carbon;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class FlightInventory extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    protected array $cascadeDeletes = ['flightInventoryTour'];
    protected $fillable = ['flight_id', 'travel_class_id', 'flight_number', 'check_in', 'departs_at', 'arrives_at', 'fit_selectable', 'stock', 'purchase_price', 'sales_price', 'currency_id', 'notes',];
    protected $casts = [
        'check_in' => 'datetime',
        'departs_at' => 'datetime',
        'arrives_at' => 'datetime',
    ];

    public static function getValidationRules(): array
    {
        return [
            'travel_class_id' => 'required|exists:travel_classes,id',
            'flight_number' => 'required',
            'check_in' => 'date',
            'departs_at' => 'date',
            'arrives_at' => 'date',
            'stock' => 'required|numeric|integer',
            'purchase_price' => 'required|numeric',
            'sales_price' => 'required|numeric',
        ];
    }

    public static function findByTour($tour_id): Collection|array
    {
        return FlightInventory::with(['tour' => function ($q) use ($tour_id) {
            $q->where('tour_id', $tour_id);
        }])->get();
    }

    public function flight(): BelongsTo
    {
        return $this->belongsTo(Flight::class, 'flight_id');
    }

    public function component(): BelongsTo
    {
        return $this->belongsTo(Flight::class, 'flight_id');
    }

    public function travelClass(): BelongsTo
    {
        return $this->belongsTo(TravelClass::class, 'travel_class_id');
    }

    public function flightInventoryTour(): HasMany
    {
        return $this->hasMany(FlightInventoryTour::class, 'flight_inventory_id');
    }

    public function tourComponents(): HasMany
    {
        return $this->hasMany(FlightInventoryTour::class, 'flight_inventory_id');
    }

    public function departureAirport(): HasOneThrough
    {
        return $this->hasOneThrough(Airport::class, Flight::class, 'departure_airport_id', 'id');
    }

    public function arrivalAirport(): HasOneThrough
    {
        return $this->hasOneThrough(Airport::class, Flight::class, 'arrival_airport_id', 'id');
    }

    public function getFlightForTourAttribute(): string
    {
        $departure_airport = $this->departureAirport->name;
        $arrival_airport = $this->arrivalAirport->name;

        $departure_date = Carbon::createFromFormat('Y-m-d H:i:s', $this->departs_at)->format('d/m/Y H:i');
        $arrival_date = Carbon::createFromFormat('Y-m-d H:i:s', $this->arrives_at)->format('d/m/Y H:i');

        $travel_class = is_null($this->travelClass) ? "" : "｜Travel Class: {$this->travelClass->name}";

        return "{$this->flight->airline->name}｜Departs from: {$departure_airport} - Arrives at: {$arrival_airport}｜Departs: {$departure_date} - Arrives: {$arrival_date}{$travel_class}";
    }

    public function getUsedStockAttribute(): int
    {
        return StockRepository::getFlightStock($this);
    }

    public function getUsedOnTourCountAttribute(): int
    {
        return $this->tourComponents()->count();
    }

    public function __toString(): string
    {
        return "{$this->component} - {$this->flight_number} ({$this->travelClass}) (" . \StringFormatter::formatDateTime($this->departs_at) . " to " . \StringFormatter::formatDateTime($this->arrives_at) . ")";
    }
}
