<?php

namespace App\Models\Booking;

use App\Models\Customer\Customer;
use App\Models\Flight\FlightInventoryTour;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\BookingFlight
 *
 * @property int $id
 * @property int $booking_id
 * @property int $customer_id
 * @property int $flight_inventory_tour_id
 * @property string $flight_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Booking $booking
 * @property-read Customer|null $customer
 * @property-read FlightInventoryTour $tourComponent
 * @method static Builder|BookingFlight newModelQuery()
 * @method static Builder|BookingFlight newQuery()
 * @method static Builder|BookingFlight query()
 * @method static Builder|BookingFlight whereBookingId($value)
 * @method static Builder|BookingFlight whereCreatedAt($value)
 * @method static Builder|BookingFlight whereCustomerId($value)
 * @method static Builder|BookingFlight whereFlightInventoryTourId($value)
 * @method static Builder|BookingFlight whereFlightType($value)
 * @method static Builder|BookingFlight whereId($value)
 * @method static Builder|BookingFlight whereUpdatedAt($value)
 * @mixin Eloquent
 */
class BookingFlight extends Model
{
    use HasFactory;

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer');
    }

    public function tourComponent(): BelongsTo
    {
        return $this->belongsTo(FlightInventoryTour::class, 'flight_inventory_tour_id');
    }
}
