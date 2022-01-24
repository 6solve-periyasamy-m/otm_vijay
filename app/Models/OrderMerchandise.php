<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderMerchandise extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['merchandise_id',];
    public $additional_attributes = ['details','tour_component_type','tour_sales_price','cost'];

    public function orderCustomer()
    {
        return $this->belongsTo(OrderCustomer::class, 'order_customer_id');
    }

    public function merchandise()
    {
        return $this->belongsTo(Merchandise::class, 'merchandise_id');
    }

    public function isCancelled(): bool
    {
        return $this->orderCustomer->order->cancelled;
    }

    public function tourComponent()
    {
        return $this->merchandise();
    }

    public function getDetailsAttribute()
    {
        return "{$this->tourComponent}";
    }

    public function getTourComponentTypeAttribute()
    {
        return $this->tourComponent->tour_component_type;
    }

    public function getTourSalesPriceAttribute()
    {
        return $this->tourComponent->tour_sales_price;
    }
}
