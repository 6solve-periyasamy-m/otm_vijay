<?php

namespace App\Models\Booking;

use App\Models\Customer\Customer;
use App\Models\Tour\Tour;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Booking
 *
 * @property int $id
 * @property int $customer_id
 * @property int $tour_id
 * @property string|null $token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $name
 * @property string|null $status
 * @property-read Collection|BookingAccommodation[] $accommodation
 * @property-read int|null $accommodation_count
 * @property-read Collection|BookingActivity[] $activities
 * @property-read int|null $activities_count
 * @property-read Customer $customer
 * @property-read Collection|BookingFlight[] $flights
 * @property-read int|null $flights_count
 * @property-read Collection|BookingMerchandise[] $merchandise
 * @property-read int|null $merchandise_count
 * @property-read Tour $tour
 * @property-read Collection|BookingTransport[] $transports
 * @property-read int|null $transports_count
 * @property-read Collection|BookingTraveller[] $travellers
 * @property-read int|null $travellers_count
 * @method static Builder|Booking newModelQuery()
 * @method static Builder|Booking newQuery()
 * @method static Builder|Booking query()
 * @method static Builder|Booking whereCreatedAt($value)
 * @method static Builder|Booking whereCustomerId($value)
 * @method static Builder|Booking whereDeletedAt($value)
 * @method static Builder|Booking whereId($value)
 * @method static Builder|Booking whereName($value)
 * @method static Builder|Booking whereStatus($value)
 * @method static Builder|Booking whereToken($value)
 * @method static Builder|Booking whereTourId($value)
 * @method static Builder|Booking whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Booking extends Model
{
    use HasFactory;

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function accommodation(): HasMany
    {
        return $this->hasMany(BookingAccommodation::class, 'booking_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(BookingActivity::class, 'booking_id');
    }

    public function flights(): HasMany
    {
        return $this->hasMany(BookingFlight::class, 'booking_id');
    }

    public function transports(): HasMany
    {
        return $this->hasMany(BookingTransport::class, 'booking_id');
    }

    public function merchandise(): HasMany
    {
        return $this->hasMany(BookingMerchandise::class, 'booking_id');
    }

    public function travellers(): HasMany
    {
        return $this->hasMany(BookingTraveller::class, 'booking_id');
    }
}
