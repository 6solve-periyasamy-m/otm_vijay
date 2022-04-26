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
 * @property-read Customer $customer
 * @property-read ActivityInventoryTour $tourComponent
 * @method static Builder|BookingActivity newModelQuery()
 * @method static Builder|BookingActivity newQuery()
 * @method static Builder|BookingActivity query()
 * @method static Builder|BookingActivity whereActivityInventoryTourId($value)
 * @method static Builder|BookingActivity whereBookingId($value)
 * @method static Builder|BookingActivity whereCreatedAt($value)
 * @method static Builder|BookingActivity whereCustomerId($value)
 * @method static Builder|BookingActivity whereId($value)
 * @method static Builder|BookingActivity whereUpdatedAt($value)
 * @mixin Eloquent
 */
class BookingActivity extends Model
{
    use HasFactory;

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function tourComponent(): BelongsTo
    {
        return $this->belongsTo(ActivityInventoryTour::class, 'activity_inventory_tour_id');
    }
}
