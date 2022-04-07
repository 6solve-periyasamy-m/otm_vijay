<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class Flight extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected array $cascadeDeletes = ['flightInventory'];
    protected $fillable = ['airline_id', 'departure_airport_id', 'arrival_airport_id', 'is_domestic', 'currency_id', 'notes', 'available_from','image_url'];
    protected $casts = ['available_from' => 'date',];

    public static function getValidationRules(): array
    {
        return [
            'airline_id' => 'required|exists:airlines,id',
            'departure_airport_id' => 'required|exists:airports,id',
            'arrival_airport_id' => 'required|exists:airports,id',
            'available_from' => 'date',
            'image' => 'nullable|image',
        ];
    }

    public function flightInventory(): HasMany
    {
        return $this->hasMany(FlightInventory::class, 'flight_id');
    }

    public function arrivalAirport(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'arrival_airport_id');
    }

    public function departureAirport(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'departure_airport_id');
    }

    public function airline(): BelongsTo
    {
        return $this->belongsTo(Airline::class, 'airline_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function getFlightDetailsAttribute(): string
    {
        return "{$this->airline->name} | Departs from: {$this->departureAirport->address->name} - Arrives at: {$this->arrivalAirport->address->name}";
    }

    public static function firstOrCreate(Airline $airline, Airport $departure, Airport $arrival, bool $isDomestic, Currency $currency, string $notes): Flight
    {
        $flight = self::where('airline_id', '=', $airline->id)
            ->where('departure_airport_id', '=', $departure->id)
            ->where('arrival_airport_id', '=', $arrival->id)
            ->where('is_domestic', '=', $isDomestic)->first();
        if ($flight == null) {
            $flight = Flight::create([
                'airline_id' => $airline->id,
                'departure_airport_id' => $departure->id,
                'arrival_airport_id' => $arrival->id,
                'is_domestic' => $isDomestic,
                'currency_id' => $currency->id,
                'notes' => $notes,
            ]);
        }
        return $flight;
    }

    public function __toString(): string
    {
        return "{$this->airline} ({$this->departureAirport} to {$this->arrivalAirport})";
    }
}
