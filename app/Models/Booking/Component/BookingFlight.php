<?php

namespace App\Models\Booking\Component;

use App\Models\Booking\BookingTraveller;
use App\Models\Flight\FlightInventoryTour;
use App\Repository\Model\Booking\Component\BookingFlightRepository;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Booking\BookingFlight
 *
 * @property int $id
 * @property int $booking_traveller_id
 * @property int $flight_inventory_tour_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read FlightInventoryTour $tourComponent
 * @property-read BookingTraveller $traveller
 * @property-read BookingFlightRepository $repository
 * @method static Builder|BookingFlight newModelQuery()
 * @method static Builder|BookingFlight newQuery()
 * @method static Builder|BookingFlight query()
 * @method static Builder|BookingFlight whereBookingTravellerId($value)
 * @method static Builder|BookingFlight whereCreatedAt($value)
 * @method static Builder|BookingFlight whereFlightInventoryTourId($value)
 * @method static Builder|BookingFlight whereId($value)
 * @method static Builder|BookingFlight whereUpdatedAt($value)
 * @mixin Eloquent
 */
class BookingFlight extends Model
{
    use HasFactory;

    public function traveller(): BelongsTo
    {
        return $this->belongsTo(BookingTraveller::class, 'booking_traveller_id');
    }

    public function tourComponent(): BelongsTo
    {
        return $this->belongsTo(FlightInventoryTour::class, 'flight_inventory_tour_id');
    }

    public static function compare(BookingFlight $a, BookingFlight $b): int
    {
        $aStart = $a->tourComponent->inventory->departs_at;
        $bStart = $b->tourComponent->inventory->departs_at;
        if ($aStart->gt($bStart)) return 1;
        if ($aStart->lt($bStart)) return -1;
        return 0;
    }

    public function getRepositoryAttribute(): BookingFlightRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new BookingFlightRepository($this);
        return $this->internal_repository;
    }
}
