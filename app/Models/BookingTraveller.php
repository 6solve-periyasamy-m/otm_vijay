<?php

namespace App\Models;

use App\Models\Customer\Customer;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\BookingTraveller
 *
 * @property int $id
 * @property int $booking_id
 * @property int $customer_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Booking $booking
 * @property-read Customer|null $customer
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

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer');
    }
}
