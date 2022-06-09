<?php

namespace App\Models\Booking;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\Booking\BookingTravellerGroup
 *
 * @property int $booking_traveller_id
 * @property int $booking_group_id
 * @property-read BookingGroup|null $group
 * @property-read BookingTraveller|null $traveller
 * @method static Builder|BookingTravellerGroup newModelQuery()
 * @method static Builder|BookingTravellerGroup newQuery()
 * @method static Builder|BookingTravellerGroup query()
 * @method static Builder|BookingTravellerGroup whereBookingGroupId($value)
 * @method static Builder|BookingTravellerGroup whereBookingTravellerId($value)
 * @mixin Eloquent
 */
class BookingTravellerGroup extends Pivot
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];
    protected $table = 'booking_traveller_groups';

    public function traveller(): BelongsTo
    {
        return $this->belongsTo(BookingTraveller::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(BookingGroup::class);
    }
}
