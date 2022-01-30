<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingAccommodation extends Model
{
    use HasFactory;

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
        return $this->belongsTo(AccommodationInventoryTour::class, 'accommodation_inventory_tour_id');
    }
}
