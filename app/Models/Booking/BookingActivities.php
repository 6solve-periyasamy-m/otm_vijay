<?php

namespace App\Models\Booking;

use App\Models\Activity\ActivityInventoryTour;
use App\Models\Customer\Customer;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
 * @property-read Booking $booking
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
        return $this->belongsTo(ActivityInventoryTour::class, 'activity_inventory_tour_id');
    }
}
