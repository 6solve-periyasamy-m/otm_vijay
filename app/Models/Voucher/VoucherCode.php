<?php

namespace App\Models\Voucher;

use App\Models\Order\Order;
use App\Models\Traits\HasRepository;
use App\Repository\Model\Voucher\VoucherCodeRepository;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Carbon;

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
 * @property-read Collection|Order[] $orders
 * @property-read Collection|OrderVoucher[] $orderVouchers
 * @property-read bool $expired Has the voucher expired
 * @property-read bool $usable Is the voucher both active and not expired
 * @property-read int|null $results_count
 * @property-read VoucherCodeRepository $repository
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
 * @mixin Eloquent
 */
class VoucherCode extends Model
{
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

    public function orders(): HasManyThrough
    {
        return $this->hasManyThrough(Order::class, OrderVoucher::class, 'voucher_code_id', 'id');
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
