<?php

namespace App\Models\Customer;

use App\Models\Order\OrderCustomer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderCustomerGroup extends Pivot
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['group_id', 'order_customer_id'];
    public $timestamps = false;

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function orderCustomer(): BelongsTo
    {
        return $this->belongsTo(OrderCustomer::class, 'order_customer_id');
    }
}
