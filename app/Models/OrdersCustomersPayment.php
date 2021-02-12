<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class OrdersCustomersPayment extends Model
{
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
