<?php

namespace App\Models\Order\Payment;

use App\Models\Customer;
use App\Models\Order\Order;
use App\Models\PaymentMethod;
use Database\Factories\PaymentFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

/**
 * App\Models\Payment
 *
 * @property int $id
 * @property int $order_id
 * @property int $payment_method_id
 * @property int|null $customer_id
 * @property string $amount
 * @property Carbon $paid_on Date when payment was made
 * @property string $payment_type Payment Type. Should be Deposit/Installment/Refund
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Customer|null $customer Customer who made the payment
 * @property-read Order $order Which order the payment is for
 * @property-read PaymentMethod $paymentMethod Which payment method was used
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

    protected $fillable = ['order_id', 'payment_method_id', 'amount', 'paid_on', 'payment_type', 'customer_id'];
    protected $casts = ['paid_on' => 'datetime',];

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

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
