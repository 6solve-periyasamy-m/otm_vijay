<?php

namespace App\Models\Order\Adjustment;

use App\Models\Order\OrderCustomer;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Order\Adjustment\OrderCustomerAdjustment
 *
 * @property int $id
 * @property int $order_customer_id
 * @property string $amount
 * @property string $reason
 * @property Carbon $date Date the adjustment was made. This is separate from created_at/updated_at, as it may be done retrospectively.
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read bool $cancelled Is the order cancelled?
 * @property-read OrderCustomer $orderCustomer Order Customer the adjustment is for.
 * @method static Builder|OrderCustomerAdjustment newModelQuery()
 * @method static Builder|OrderCustomerAdjustment newQuery()
 * @method static QueryBuilder|OrderCustomerAdjustment onlyTrashed()
 * @method static Builder|OrderCustomerAdjustment query()
 * @method static Builder|OrderCustomerAdjustment whereAmount($value)
 * @method static Builder|OrderCustomerAdjustment whereCreatedAt($value)
 * @method static Builder|OrderCustomerAdjustment whereDate($value)
 * @method static Builder|OrderCustomerAdjustment whereDeletedAt($value)
 * @method static Builder|OrderCustomerAdjustment whereId($value)
 * @method static Builder|OrderCustomerAdjustment whereOrderCustomerId($value)
 * @method static Builder|OrderCustomerAdjustment whereReason($value)
 * @method static Builder|OrderCustomerAdjustment whereUpdatedAt($value)
 * @method static QueryBuilder|OrderCustomerAdjustment withTrashed()
 * @method static QueryBuilder|OrderCustomerAdjustment withoutTrashed()
 * @mixin Eloquent
 */
class OrderCustomerAdjustment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['order_customer_id', 'amount', 'reason', 'date'];
    protected $casts = ['date' => 'datetime'];

    public static function getValidationRules(): array
    {
        return ['date' => 'required|date', 'amount' => 'required|numeric',];
    }

    public function orderCustomer(): BelongsTo
    {
        return $this->belongsTo(OrderCustomer::class, 'order_customer_id');
    }

    public function getCancelledAttribute(): bool
    {
        return $this->orderCustomer->order->cancelled;
    }
}
