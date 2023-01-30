<?php

namespace App\Models\Voucher;

use App\Models\Order\Order;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Voucher\OrderVoucher
 *
 * @property int $id
 * @property int $order_id
 * @property int $voucher_code_id
 * @property Carbon|null $entered
 * @property Carbon|null $applied
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Order $order
 * @property-read VoucherCode $voucher
 * @method static Builder|OrderVoucher newModelQuery()
 * @method static Builder|OrderVoucher newQuery()
 * @method static Builder|OrderVoucher query()
 * @method static Builder|OrderVoucher whereApplied($value)
 * @method static Builder|OrderVoucher whereCreatedAt($value)
 * @method static Builder|OrderVoucher whereEntered($value)
 * @method static Builder|OrderVoucher whereId($value)
 * @method static Builder|OrderVoucher whereOrderId($value)
 * @method static Builder|OrderVoucher whereUpdatedAt($value)
 * @method static Builder|OrderVoucher whereVoucherCodeId($value)
 * @mixin Eloquent
 */
class OrderVoucher extends Model
{
    protected $casts = ['applied' => 'datetime', 'entered' => 'datetime',];
    protected $with = ['voucher',];
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(VoucherCode::class, 'voucher_code_id');
    }
}
