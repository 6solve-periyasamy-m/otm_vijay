<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderCustomerGroup extends Pivot
{
    use HasFactory;
    protected $fillable = ['group_id', 'order_customer_id'];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function orderCustomer()
    {
        return $this->belongsTo(OrderCustomer::class, 'order_customer_id');
    }
}
