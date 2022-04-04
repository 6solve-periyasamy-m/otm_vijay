<?php

namespace App\Models;

use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

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
