<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderCustomerGroup extends Pivot
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['group_id', 'order_customer_id'];
    public $timestamps = false;

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function orderCustomer()
    {
        return $this->belongsTo(OrderCustomer::class, 'order_customer_id');
    }
}
