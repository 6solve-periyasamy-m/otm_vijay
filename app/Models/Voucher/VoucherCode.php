<?php

namespace App\Models\Voucher;

use App\Models\Booking\BookingTraveller;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Models\Tour\Tour;
use App\Models\Traits\HasRepository;
use App\Repository\Model\Voucher\VoucherCodeRepository;
use Database\Factories\Voucher\VoucherCodeFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

/**
 * App\Models\Voucher\VoucherCode
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $code
 * @property bool $active
 * @property bool $global Should the voucher code be accepted on all tours?
 * @property int $limit How many times can a voucher code be claimed
 * @property Carbon $expiry
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|VoucherCodeResult[] $results
 * @property-read Collection|Order[] $order_customers
 * @property-read Collection|OrderVoucher[] $orderVouchers
 * @property-read bool $expired Has the voucher expired
 * @property-read bool $usable Is the voucher both active and not expired
 * @property-read int|null $order_vouchers_count
 * @property-read int|null $orders_count
 * @property-read int|null $results_count
 * @property-read VoucherCodeRepository $repository
 * @property-read Collection<int, Tour> $excluded
 * @property-read int|null $excluded_count
 * @property-read Collection<int, Tour> $included
 * @property-read int|null $included_count
 * @method static Builder|VoucherCode whereActive($value)
 * @method static Builder|VoucherCode whereCode($value)
 * @method static Builder|VoucherCode whereCreatedAt($value)
 * @method static Builder|VoucherCode whereDescription($value)
 * @method static Builder|VoucherCode whereId($value)
 * @method static Builder|VoucherCode whereName($value)
 * @method static Builder|VoucherCode whereUpdatedAt($value)
 * @method static Builder|VoucherCode newModelQuery()
 * @method static Builder|VoucherCode newQuery()
 * @method static Builder|VoucherCode query()
 * @method static VoucherCodeFactory factory($count = null, $state = [])
 * @method static Builder|VoucherCode whereExpiry($value)
 * @method static Builder|VoucherCode whereGlobal($value)
 * @mixin Eloquent
 */
class VoucherCode extends Model
{
    use HasRelationships;
    use HasFactory;
    use HasRepository;

    protected $guarded = [];
    protected $casts = ['active' => 'boolean', 'global' => 'boolean', 'expiry' => 'date:Y-m-d'];
    protected $with = ['results',];

    public function results(): HasMany
    {
        return $this->hasMany(VoucherCodeResult::class, 'voucher_code_id');
    }

    public function orderVouchers(): HasMany
    {
        return $this->hasMany(OrderVoucher::class, 'voucher_code_id');
    }

    public function orderCustomers(): BelongsToMany
    {
        return $this->belongsToMany(OrderCustomer::class, OrderVoucher::class)->withPivot(['entered', 'applied']);
    }

    public function bookingTravellers(): BelongsToMany
    {
        return $this->belongsToMany(BookingTraveller::class, VoucherBooking::class)->withTimestamps()->with('booking');
    }

    protected function tours(): BelongsToMany
    {
        return $this->belongsToMany(Tour::class, 'voucher_tours')->withPivot(['invert']);
    }

    public function included(): BelongsToMany
    {
        return $this->tours()->where('invert', '=', 0);
    }

    public function excluded(): BelongsToMany
    {
        return $this->tours()->where('invert', '=', 1);
    }

    public function include(Tour|int $tour): void
    {
        if ($tour instanceof Tour) $tour = $tour->id;
        $this->tours()->attach($tour, ['invert' => 0,]);
    }

    public function exclude(Tour|int $tour): void
    {
        if ($tour instanceof Tour) $tour = $tour->id;
        $this->tours()->attach($tour, ['invert' => 1,]);
    }

    public function detach(Tour|int $tour): void
    {
        if ($tour instanceof Tour) $tour = $tour->id;
        $this->tours()->detach($tour);
    }

    public function getExpiredAttribute(): bool
    {
        return $this->expiry->lt(now());
    }

    public function getUsableAttribute(): bool
    {
        return !$this->expired && $this->active;
    }
}
