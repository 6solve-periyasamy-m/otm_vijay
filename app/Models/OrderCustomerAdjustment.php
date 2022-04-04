<?php

namespace App\Models;

use App\Models\Order\OrderCustomer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderCustomerAdjustment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['order_customer_id', 'amount', 'reason', 'date'];
    protected $casts = ['date' => 'datetime'];

    public static function getValidationRules()
    {
        return ['date' => 'required|date', 'amount' => 'required|numeric',];
    }

    public function orderCustomer()
    {
        return $this->belongsTo(OrderCustomer::class, 'order_customer_id');
    }



    public function isCancelled(): bool
    {
        return $this->orderCustomer->order->cancelled;
    }
}
