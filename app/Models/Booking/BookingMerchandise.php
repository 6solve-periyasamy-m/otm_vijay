<?php

namespace App\Models\Booking;

use App\Models\Customer\Customer;
use App\Models\Tour\Merchandise;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Booking\BookingMerchandise
 *
 * @property int $id
 * @property int $booking_id
 * @property int $customer_id
 * @property int $merchandise_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Booking $booking
 * @property-read Customer $customer
 * @property-read Merchandise $tourComponent
 * @method static Builder|BookingMerchandise newModelQuery()
 * @method static Builder|BookingMerchandise newQuery()
 * @method static Builder|BookingMerchandise query()
 * @method static Builder|BookingMerchandise whereBookingId($value)
 * @method static Builder|BookingMerchandise whereCreatedAt($value)
 * @method static Builder|BookingMerchandise whereCustomerId($value)
 * @method static Builder|BookingMerchandise whereId($value)
 * @method static Builder|BookingMerchandise whereMerchandiseId($value)
 * @method static Builder|BookingMerchandise whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read \App\Models\Booking\BookingTraveller $traveller
 */
class BookingMerchandise extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'merchandise_id', 'booking_id'];

    public function traveller(): BelongsTo
    {
        return $this->belongsTo(BookingTraveller::class, 'booking_traveller_id');
    }

    public function tourComponent(): BelongsTo
    {
        return $this->belongsTo(Merchandise::class, 'merchandise_id');
    }
}
