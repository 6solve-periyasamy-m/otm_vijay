<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['quote_id','tour_id','lead_booker_id','token','booking_reference','ordered_on','internal_notes','external_notes',];

    public function quote() 
    {
        return $this->hasOne(Quote::class);
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function orderStatus()
    {
        return $this->hasOne(OrderStatus::class);
    }

    public function orderCustomers() {
        return $this->hasMany(OrdersCustomer::class, 'order_id');
    }

    public function payments() {
        return $this->hasMany(Payment::class, 'order_id');
    }

    public function leadBooker() {
        return $this->belongsTo(OrdersCustomer::class, 'lead_booker_id');
    }

    public function adjustments() {
        return $this->hasMany(ManualAdjustment::class, 'order_id');
    }
}
