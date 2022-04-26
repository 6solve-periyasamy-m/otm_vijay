<?php

namespace App\Models\Booking;

use App\Models\Customer\Customer;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\BookingTraveller
 *
 * @property int $id
 * @property int $booking_id
 * @property int $customer_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|BookingAccommodation[] $accommodation
 * @property-read int|null $accommodation_count
 * @property-read Collection|BookingActivity[] $activities
 * @property-read int|null $activities_count
 * @property-read Booking $booking
 * @property-read Customer|null $customer
 * @property-read Collection|BookingFlight[] $flights
 * @property-read int|null $flights_count
 * @property-read Collection|BookingMerchandise[] $merchandise
 * @property-read int|null $merchandise_count
 * @property-read Collection|BookingTransport[] $transport
 * @property-read int|null $transport_count
 * @method static Builder|BookingTraveller newModelQuery()
 * @method static Builder|BookingTraveller newQuery()
 * @method static Builder|BookingTraveller query()
 * @method static Builder|BookingTraveller whereBookingId($value)
 * @method static Builder|BookingTraveller whereCreatedAt($value)
 * @method static Builder|BookingTraveller whereCustomerId($value)
 * @method static Builder|BookingTraveller whereId($value)
 * @method static Builder|BookingTraveller whereUpdatedAt($value)
 * @mixin Eloquent
 */
class BookingTraveller extends Model
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

    public function accommodation(): HasMany
    {
        return $this->hasMany(BookingAccommodation::class, 'customer_id', 'customer_id')->where('booking_id', $this->booking_id);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(BookingActivity::class, 'customer_id', 'customer_id')->where('booking_id', $this->booking_id);
    }

    public function flights(): HasMany
    {
        return $this->hasMany(BookingFlight::class, 'customer_id', 'customer_id')->where('booking_id', $this->booking_id);
    }

    public function transport(): HasMany
    {
        return $this->hasMany(BookingTransport::class, 'customer_id', 'customer_id')->where('booking_id', $this->booking_id);
    }

    public function merchandise(): HasMany
    {
        return $this->hasMany(BookingMerchandise::class, 'customer_id', 'customer_id')->where('booking_id', $this->booking_id);
    }
}
