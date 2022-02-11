<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    public function orderCustomers()
    {
        return $this->belongsToMany(OrderCustomer::class, 'order_customer_groups', 'order_customer_id', 'group_id');
    }
}
