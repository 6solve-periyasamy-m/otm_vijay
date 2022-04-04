<?php

namespace App\Models;

use App\Models\Order\Order;
use App\Repository\OrderRepository;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\OrderInstallment
 *
 * @property int $id
 * @property int $order_id
 * @property float $amount
 * @property Carbon|null $due_on
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read float $calculated_amount Calculated installment amount based on customer count
 * @property-read bool $cancelled Is the order cancelled?
 * @property-read bool $paid Is the installment paid?
 * @property-read float $percentage Percentage of the order amount
 * @property-read Order $order Related order
 * @method static Builder|OrderInstallment newModelQuery()
 * @method static Builder|OrderInstallment newQuery()
 * @method static QueryBuilder|OrderInstallment onlyTrashed()
 * @method static Builder|OrderInstallment query()
 * @method static Builder|OrderInstallment whereAmount($value)
 * @method static Builder|OrderInstallment whereCreatedAt($value)
 * @method static Builder|OrderInstallment whereDeletedAt($value)
 * @method static Builder|OrderInstallment whereDueOn($value)
 * @method static Builder|OrderInstallment whereId($value)
 * @method static Builder|OrderInstallment whereOrderId($value)
 * @method static Builder|OrderInstallment whereUpdatedAt($value)
 * @method static QueryBuilder|OrderInstallment withTrashed()
 * @method static QueryBuilder|OrderInstallment withoutTrashed()
 * @mixin Eloquent
 */
class OrderInstallment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['amount', 'due_on',];
    protected $casts = ['due_on' => 'date'];

    public static function getValidationRules(): array
    {
        return ['due_on' => 'required|date', 'amount' => 'required|numeric',];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function getCancelledAttribute(): bool
    {
        return $this->order->cancelled;
    }

    public function getPaidAttribute(): bool
    {
        return OrderRepository::isInstallmentPaid($this);
    }

    public function getPercentageAttribute(): float
    {
        return $this->order->cost == 0 ? 100 : round((($this->amount * $this->order->customer_count) / $this->order->cost) * 100, 2);
    }

    public function getCalculatedAmountAttribute(): float
    {
        return $this->amount * $this->order->customer_count;
    }
}
