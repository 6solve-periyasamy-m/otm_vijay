<?php

namespace App\Models\Booking;

use App\Models\Activity\ActivityInventoryTour;
use App\Models\Customer\Customer;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Carbon;

/**
 * App\Models\Booking\BookingActivity
 *
 * @property int $id
 * @property int $booking_traveller_id
 * @property int $activity_inventory_tour_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read ActivityInventoryTour $tourComponent
 * @property-read \App\Models\Booking\BookingTraveller $traveller
 * @method static Builder|BookingActivity newModelQuery()
 * @method static Builder|BookingActivity newQuery()
 * @method static Builder|BookingActivity query()
 * @method static Builder|BookingActivity whereActivityInventoryTourId($value)
 * @method static Builder|BookingActivity whereBookingTravellerId($value)
 * @method static Builder|BookingActivity whereCreatedAt($value)
 * @method static Builder|BookingActivity whereId($value)
 * @method static Builder|BookingActivity whereUpdatedAt($value)
 * @mixin Eloquent
 */
class BookingActivity extends Model
{
    use HasFactory;

    public function traveller(): BelongsTo
    {
        return $this->belongsTo(BookingTraveller::class, 'booking_traveller_id');
    }

    public function tourComponent(): BelongsTo
    {
        return $this->belongsTo(ActivityInventoryTour::class, 'activity_inventory_tour_id');
    }

    public static function compare(BookingActivity $a, BookingActivity $b): int
    {
        $aStart = $a->tourComponent->inventory->starts_at;
        $bStart = $b->tourComponent->inventory->starts_at;
        if ($aStart->gt($bStart)) return 1;
        if ($aStart->lt($bStart)) return -1;
        return 0;
    }
}
