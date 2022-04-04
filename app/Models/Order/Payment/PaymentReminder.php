<?php

namespace App\Models\Order\Payment;

use App\Models\Order\Order;
use App\Models\Order\OrderInstallment;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Order\Payment\PaymentReminder
 *
 * @property int $id
 * @property int $order_id
 * @property int $order_installment_id
 * @property int $period Number of days before the payment date. Overdue payments are negative.
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read OrderInstallment|null $installment
 * @property-read Order $order
 * @method static Builder|PaymentReminder newModelQuery()
 * @method static Builder|PaymentReminder newQuery()
 * @method static Builder|PaymentReminder query()
 * @method static Builder|PaymentReminder whereCreatedAt($value)
 * @method static Builder|PaymentReminder whereId($value)
 * @method static Builder|PaymentReminder whereOrderId($value)
 * @method static Builder|PaymentReminder whereOrderInstallmentId($value)
 * @method static Builder|PaymentReminder wherePeriod($value)
 * @method static Builder|PaymentReminder whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PaymentReminder extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'order_installment_id'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function installment(): BelongsTo
    {
        return $this->belongsTo(OrderInstallment::class);
    }
}
