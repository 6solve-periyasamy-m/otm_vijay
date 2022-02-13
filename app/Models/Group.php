<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    use CascadeSoftDeletes;

    protected $cascadeDeletes = ['pivot', 'rooms'];

    public function orderCustomers()
    {
        return $this->belongsToMany(OrderCustomer::class, 'order_customer_groups', 'order_customer_id', 'group_id');
    }
}
