<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderCustomerAdjustment extends Model
{
    use HasFactory, SoftDeletes;

    public function orderCustomer() {
        return $this->belongsTo(OrdersCustomer::class, 'order_customer_id');
    }
}
