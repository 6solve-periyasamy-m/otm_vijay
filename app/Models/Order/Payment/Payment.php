<?php

namespace App\Models\Order\Payment;

use App\Models\Customer\Agent;
use App\Models\Customer\Customer;
use App\Models\Helper\Model;
use App\Models\Order\Order;
use Database\Factories\PaymentFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

/**
 * App\Models\Order\Payment\Payment
 *
 * @property int $id
 * @property int $order_id
 * @property int $payment_method_id
 * @property int|null $customer_id
 * @property int|null $payer_id
 * @property string|null $payer_type
 * @property float $amount
 * @property float|null $payment_fee
 * @property Carbon $paid_on Date when payment was made
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Agent|Customer|null $payer
 * @property-read string|null $payer_name The name of the payer
 * @property-read Order $order Which order the payment is for
 * @property-read PaymentMethod $paymentMethod Which payment method was used
 * @property-read string $payment_type Payment Type. Will be payment or refund
 * @method static PaymentFactory factory(...$parameters)
 * @method static Builder|Payment newModelQuery()
 * @method static Builder|Payment newQuery()
 * @method static QueryBuilder|Payment onlyTrashed()
 * @method static Builder|Payment query()
 * @method static Builder|Payment whereAmount($value)
 * @method static Builder|Payment whereCreatedAt($value)
 * @method static Builder|Payment whereCustomerId($value)
 * @method static Builder|Payment whereDeletedAt($value)
 * @method static Builder|Payment whereId($value)
 * @method static Builder|Payment whereOrderId($value)
 * @method static Builder|Payment wherePaidOn($value)
 * @method static Builder|Payment wherePaymentMethodId($value)
 * @method static Builder|Payment wherePaymentType($value)
 * @method static Builder|Payment whereUpdatedAt($value)
 * @method static QueryBuilder|Payment withTrashed()
 * @method static QueryBuilder|Payment withoutTrashed()
 * @mixin Eloquent
 */
class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $casts = ['paid_on' => 'datetime:Y-m-d H:i:s', 'amount' => 'double', 'payment_fee' => 'double'];

    public static function getValidationRules(): array
    {
        return [
            'payment_method_id' => 'required|exists:payment_methods,id',
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric',
            'paid_on' => 'required|date',
            'payment_type' => [
                'required',
                Rule::in(['Deposit', 'Installment', 'Refund'])
            ],
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function payer(): MorphTo
    {
        return $this->morphTo('payer', 'payer_type', 'payer_id', 'id');
    }

    public function getPayerNameAttribute(): string|null
    {
        if ($this->payer instanceof Agent) {
            return $this->payer->name;
        }
        if ($this->payer instanceof Customer) {
            return $this->payer->full_name;
        }
        return null;
    }

    public function totalWithFee(): float
    {
        return $this->amount + ($this->payment_fee ?? 0.0);
    }

    public function getPaymentTypeAttribute(): string
    {
        return $this->amount >= 0 ? "Payment" : "Refund";
    }
}
