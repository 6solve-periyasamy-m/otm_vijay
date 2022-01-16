<?php

namespace App\Models;

use Carbon\Carbon;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Flight extends Model
{
    use HasFactory;
    use SoftDeletes, CascadeSoftDeletes;

    public $additional_attributes = ['flight_details'];
    protected $cascadeDeletes = ['flightInventory'];
    protected $fillable = ['airline_id', 'departure_airport_id', 'arrival_airport_id', 'is_domestic', 'currency_id', 'notes', 'available_after','image_url'];
    protected $casts = ['available_after' => 'date',];

    public static function getValidationRules()
    {
        return [
            'airline_id' => 'required|exists:airlines,id',
            'departure_airport_id' => 'required|exists:airports,id',
            'arrival_airport_id' => 'required|exists:airports,id',
            'available_after' => 'date',
            'image' => 'nullable|image',
        ];
    }

    public function flightInventory()
    {
        return $this->hasMany(FlightInventory::class);
    }

    public function arrivalAirport()
    {
        return $this->belongsTo(Airport::class, 'arrival_airport_id', 'id');
    }

    public function departureAirport()
    {
        return $this->belongsTo(Airport::class, 'departure_airport_id', 'id');
    }

    public function airline()
    {
        return $this->belongsTo(Airline::class);
    }

    public function getFlightDetailsAttribute()
    {
        $departs = Carbon::parse($this->departure_date)->format('d/m/Y');
        $arrives = Carbon::parse($this->arrival_date)->format('d/m/Y');

        return "{$this->airline->name} | Departs from: {$this->departureAirport->address->name} - Arrives at: {$this->arrivalAirport->address->name}";
        //return "{$this->airline->name} | Departs {$departs} from: {$this->departureAirport->location->name} - Arrives {$arrives} at: {$this->arrivalAirport->location->name} ";
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public static function firstOrCreate(Airline $airline, Airport $departure, Airport $arrival, bool $isDomestic, Currency $currency, string $notes)
    {
        $flight = self::where('airline_id', '=', $airline->id)
            ->andWhere('departure_airport_id', '=', $departure->id)
            ->andWhere('arrival_airport_id', '=', $arrival->id)
            ->andWhere('is_domestic', '=', $isDomestic)->first();
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
}
