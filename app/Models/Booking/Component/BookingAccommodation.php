<?php

namespace App\Models\Booking\Component;

use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Booking\BookingGroup;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Booking\BookingAccommodation
 *
 * @property int $id
 * @property int $booking_group_id
 * @property int $accommodation_inventory_tour_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read BookingGroup $group
 * @property-read AccommodationInventoryTour $tourComponent
 * @method static Builder|BookingAccommodation newModelQuery()
 * @method static Builder|BookingAccommodation newQuery()
 * @method static Builder|BookingAccommodation query()
 * @method static Builder|BookingAccommodation whereAccommodationInventoryTourId($value)
 * @method static Builder|BookingAccommodation whereBookingGroupId($value)
 * @method static Builder|BookingAccommodation whereCreatedAt($value)
 * @method static Builder|BookingAccommodation whereId($value)
 * @method static Builder|BookingAccommodation whereUpdatedAt($value)
 * @mixin Eloquent
 */
class BookingAccommodation extends Model
{
    use HasFactory;

    public function group(): BelongsTo
    {
        return $this->belongsTo(BookingGroup::class, 'booking_group_id');
    }

    public function tourComponent(): BelongsTo
    {
        return $this->belongsTo(AccommodationInventoryTour::class, 'accommodation_inventory_tour_id');
    }

    public static function compare(BookingAccommodation $a, BookingAccommodation $b): int
    {
        $aStart = $a->tourComponent->inventory->check_in;
        $bStart = $b->tourComponent->inventory->check_in;
        if ($aStart->gt($bStart)) return 1;
        if ($aStart->lt($bStart)) return -1;
        return 0;
    }
}
