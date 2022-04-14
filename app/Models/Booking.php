<?php

namespace App\Models;

use App\Models\Tour\Tour;
use App\Models\Customer\Customer;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
 * @property-read Collection|\App\Models\BookingAccommodation[] $accommodation
 * @property-read int|null $accommodation_count
 * @property-read Collection|\App\Models\BookingActivities[] $activities
 * @property-read int|null $activities_count
 * @property-read Customer $customer
 * @property-read Collection|\App\Models\BookingFlight[] $flights
 * @property-read int|null $flights_count
 * @property-read Tour $tour
 * @property-read Collection|\App\Models\BookingTransport[] $transports
 * @property-read int|null $transports_count
 * @property-read Collection|\App\Models\BookingTraveller[] $travellers
 * @property-read int|null $travellers_count
 * @method static Builder|Booking newModelQuery()
 * @method static Builder|Booking newQuery()
 * @method static Builder|Booking query()
 * @method static Builder|Booking whereCreatedAt($value)
 * @method static Builder|Booking whereCustomerId($value)
 * @method static Builder|Booking whereDeletedAt($value)
 * @method static Builder|Booking whereId($value)
 * @method static Builder|Booking whereName($value)
 * @method static Builder|Booking whereToken($value)
 * @method static Builder|Booking whereTourId($value)
 * @method static Builder|Booking whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Booking extends Model
{
    use HasFactory;

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function accommodation()
    {
        return $this->hasMany(BookingAccommodation::class, 'booking_id');
    }

    public function activities()
    {
        return $this->hasMany(BookingActivities::class, 'booking_id');
    }

    public function flights()
    {
        return $this->hasMany(BookingFlight::class, 'booking_id');
    }

    public function transports()
    {
        return $this->hasMany(BookingTransport::class, 'booking_id');
    }

    public function travellers()
    {
        return $this->hasMany(BookingTraveller::class, 'booking_id');
    }
}
