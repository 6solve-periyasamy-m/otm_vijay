<?php

namespace App\Models\Booking;

use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Accommodation\RoomType;
use App\Models\Customer\Customer;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\BookingAccommodation
 *
 * @property int $id
 * @property int $booking_id
 * @property int $customer_id
 * @property int $accommodation_inventory_tour_id
 * @property int $room_type_id
 * @property int|null $group_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Booking $booking
 * @property-read Customer $customer
 * @property-read AccommodationGroup|null $group
 * @property-read RoomType $roomType
 * @property-read AccommodationInventoryTour $tourComponent
 * @method static Builder|BookingAccommodation newModelQuery()
 * @method static Builder|BookingAccommodation newQuery()
 * @method static Builder|BookingAccommodation query()
 * @method static Builder|BookingAccommodation whereAccommodationInventoryTourId($value)
 * @method static Builder|BookingAccommodation whereBookingId($value)
 * @method static Builder|BookingAccommodation whereCreatedAt($value)
 * @method static Builder|BookingAccommodation whereCustomerId($value)
 * @method static Builder|BookingAccommodation whereGroupId($value)
 * @method static Builder|BookingAccommodation whereId($value)
 * @method static Builder|BookingAccommodation whereRoomTypeId($value)
 * @method static Builder|BookingAccommodation whereUpdatedAt($value)
 * @mixin Eloquent
 */
class BookingAccommodation extends Model
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
        return $this->belongsTo(AccommodationInventoryTour::class, 'accommodation_inventory_tour_id');
    }

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(AccommodationGroup::class, 'group_id');
    }
}
