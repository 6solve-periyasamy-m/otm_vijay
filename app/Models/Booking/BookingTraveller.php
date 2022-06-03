<?php

namespace App\Models\Booking;

use App\Models\Customer\Customer;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships as HasDeepRelation;

/**
 * App\Models\Booking\BookingTraveller
 *
 * @property int $id
 * @property int $booking_id
 * @property int|null $customer_id
 * @property string|null $title
 * @property string|null $first_name
 * @property string|null $middle_names
 * @property string|null $last_name
 * @property string|null $date_of_birth
 * @property string|null $mobile_number
 * @property string|null $email_address
 * @property int|null $home_address_id
 * @property int|null $billing_address_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|BookingActivity[] $activities
 * @property-read int|null $activities_count
 * @property-read Booking $booking
 * @property-read Customer|null $customer
 * @property-read Collection|BookingFlight[] $flights
 * @property-read int|null $flights_count
 * @property-read Collection|BookingMerchandise[] $merchandise
 * @property-read int|null $merchandise_count
 * @property-read Collection|BookingTransport[] $transport
 * @property-read int|null $transport_count
 * @method static Builder|BookingTraveller newModelQuery()
 * @method static Builder|BookingTraveller newQuery()
 * @method static Builder|BookingTraveller query()
 * @method static Builder|BookingTraveller whereBillingAddressId($value)
 * @method static Builder|BookingTraveller whereBookingId($value)
 * @method static Builder|BookingTraveller whereCreatedAt($value)
 * @method static Builder|BookingTraveller whereCustomerId($value)
 * @method static Builder|BookingTraveller whereDateOfBirth($value)
 * @method static Builder|BookingTraveller whereEmailAddress($value)
 * @method static Builder|BookingTraveller whereFirstName($value)
 * @method static Builder|BookingTraveller whereHomeAddressId($value)
 * @method static Builder|BookingTraveller whereId($value)
 * @method static Builder|BookingTraveller whereLastName($value)
 * @method static Builder|BookingTraveller whereMiddleNames($value)
 * @method static Builder|BookingTraveller whereMobileNumber($value)
 * @method static Builder|BookingTraveller whereTitle($value)
 * @method static Builder|BookingTraveller whereUpdatedAt($value)
 * @mixin Eloquent
 */
class BookingTraveller extends Model
{
    use HasFactory;
    use HasDeepRelation;

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer');
    }

    public function accommodation(): HasManyDeep
    {
        return $this->hasManyDeep(BookingAccommodation::class, [BookingTravellerGroup::class, BookingGroup::class]);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(BookingActivity::class, 'booking_traveller_id');
    }

    public function flights(): HasMany
    {
        return $this->hasMany(BookingFlight::class, 'booking_traveller_id');
    }

    public function transport(): HasMany
    {
        return $this->hasMany(BookingTransport::class, 'booking_traveller_id');
    }

    public function merchandise(): HasMany
    {
        return $this->hasMany(BookingMerchandise::class, 'booking_traveller_id');
    }
}
