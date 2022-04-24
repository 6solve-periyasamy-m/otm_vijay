<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingAccommodation extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'room_type_id', 'group_id', 'accommodation_inventory_tour_id', 'booking_id'];

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

    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }

    public function group()
    {
        return $this->belongsTo(AccommodationGroup::class, 'group_id');
    }
}
