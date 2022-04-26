<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingTransport extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'transport_inventory_tour_id', 'booking_id'];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer');
    }

    public function tourComponent()
    {
        return $this->belongsTo(TransportInventoryTour::class, 'transport_inventory_tour_id');
    }
}
