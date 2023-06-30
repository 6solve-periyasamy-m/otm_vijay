<?php

namespace App\Models\Voucher;

use App\Models\Booking\BookingTraveller;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Voucher\VoucherBooking
 *
 * @property int $id
 * @property int $booking_traveller_id
 * @property int $voucher_code_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read BookingTraveller $traveller
 * @property-read VoucherCode $voucher
 * @method static Builder|VoucherBooking newModelQuery()
 * @method static Builder|VoucherBooking newQuery()
 * @method static Builder|VoucherBooking query()
 * @method static Builder|VoucherBooking whereBookingTravellerId($value)
 * @method static Builder|VoucherBooking whereCreatedAt($value)
 * @method static Builder|VoucherBooking whereId($value)
 * @method static Builder|VoucherBooking whereUpdatedAt($value)
 * @method static Builder|VoucherBooking whereVoucherCodeId($value)
 * @mixin Eloquent
 */
class VoucherBooking extends Model
{
    public function traveller(): BelongsTo
    {
        return $this->belongsTo(BookingTraveller::class, 'booking_traveller_id');
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(VoucherCode::class, 'voucher_code_id');
    }
}
