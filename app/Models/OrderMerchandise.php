<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderMerchandise extends Model
{
    use HasFactory;
    protected $fillable = ['merchandise_id',];

    public function orderCustomer()
    {
        return $this->belongsTo(OrderCustomer::class, 'order_customer_id');
    }

    public function merchandise()
    {
        return $this->belongsTo(Merchandise::class, 'merchandise_id');
    }
}
