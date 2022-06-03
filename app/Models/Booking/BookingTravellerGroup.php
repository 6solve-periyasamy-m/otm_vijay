<?php

namespace App\Models\Booking;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Booking\BookingTravellerGroup
 *
 * @property int $booking_traveller_id
 * @property int $booking_group_id
 * @property-read \App\Models\Booking\BookingGroup|null $group
 * @property-read \App\Models\Booking\BookingTraveller|null $traveller
 * @method static \Illuminate\Database\Eloquent\Builder|BookingTravellerGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BookingTravellerGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BookingTravellerGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|BookingTravellerGroup whereBookingGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookingTravellerGroup whereBookingTravellerId($value)
 * @mixin \Eloquent
 */
class BookingTravellerGroup extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function traveller(): BelongsTo
    {
        return $this->belongsTo(BookingTraveller::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(BookingGroup::class);
    }
}
