<?php

namespace App\Models;

use App\Models\Activity\ActivityInventoryTour;
use App\Models\Customer\Customer;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\BookingActivities
 *
 * @property int $id
 * @property int $booking_id
 * @property int $customer_id
 * @property int $activity_inventory_tour_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Booking $booking
 * @property-read Customer|null $customer
 * @property-read ActivityInventoryTour $tourComponent
 * @method static Builder|BookingActivities newModelQuery()
 * @method static Builder|BookingActivities newQuery()
 * @method static Builder|BookingActivities query()
 * @method static Builder|BookingActivities whereActivityInventoryTourId($value)
 * @method static Builder|BookingActivities whereBookingId($value)
 * @method static Builder|BookingActivities whereCreatedAt($value)
 * @method static Builder|BookingActivities whereCustomerId($value)
 * @method static Builder|BookingActivities whereId($value)
 * @method static Builder|BookingActivities whereUpdatedAt($value)
 * @mixin Eloquent
 */
class BookingActivities extends Model
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

    public function tourComponent()
    {
        return $this->belongsTo(ActivityInventoryTour::class, 'activity_inventory_tour_id');
    }
}
