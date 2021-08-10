<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class OrdersCustomersPayment extends Model
{
    use SoftDeletes;

    public function orderCustomer() 
    {
        return $this->belongsTo(OrderCustomer::class);
    }

    public function paymentType() 
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function paymentMethod() 
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
