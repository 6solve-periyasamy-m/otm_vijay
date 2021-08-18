<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdersCustomer extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function order() 
    {
        return $this->belongsTo(Order::class);
    }

    public function customer() 
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderAccommodation()
    {
        return $this->hasMany(OrdersAccommodation::class, 'id');
    }

}
