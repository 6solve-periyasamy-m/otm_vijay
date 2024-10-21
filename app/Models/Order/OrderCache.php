<?php

namespace App\Models\Order;

use App\Models\Helper\Enum\OrderStatus;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Order\OrderCache
 *
 * @property int $id
 * @property int $order_id
 * @property int $status
 * @property float|null $commission_amount
 * @property float $cost
 * @property float $total_owed
 * @property Carbon|null $next_payment_date
 * @property float|null $next_payment_amount
 * @property float|null $next_payment_remaining
 * @property float|null $cost_to_company
 * @property Carbon $cached
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Order $order
 * @method static Builder|OrderCache newModelQuery()
 * @method static Builder|OrderCache newQuery()
 * @method static Builder|OrderCache query()
 * @method static Builder|OrderCache whereCached($value)
 * @method static Builder|OrderCache whereCommissionAmount($value)
 * @method static Builder|OrderCache whereCost($value)
 * @method static Builder|OrderCache whereCreatedAt($value)
 * @method static Builder|OrderCache whereId($value)
 * @method static Builder|OrderCache whereNextPaymentAmount($value)
 * @method static Builder|OrderCache whereNextPaymentDate($value)
 * @method static Builder|OrderCache whereNextPaymentRemaining($value)
 * @method static Builder|OrderCache whereOrderId($value)
 * @method static Builder|OrderCache whereStatus($value)
 * @method static Builder|OrderCache whereTotalOwed($value)
 * @method static Builder|OrderCache whereUpdatedAt($value)
 * @mixin Eloquent
 */
class OrderCache extends Model
{
    protected $casts = [
        'status' => OrderStatus::class,
        'commission_amount' => 'float',
        'cost' => 'float',
        'total_owed' => 'float',
        'next_payment_amount' => 'float',
        'next_payment_remaining' => 'float',
        'next_payment_date' => 'date',
        'cached' => 'datetime',
    ];

    protected $guarded = [];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
