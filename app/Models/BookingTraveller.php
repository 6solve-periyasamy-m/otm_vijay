<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class BookingTraveller extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'booking_id'];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function accommodation(): HasManyThrough
    {
        return $this->hasManyThrough(BookingAccommodation::class, Booking::class, 'id', 'booking_id', 'booking_id');
    }

    public function activities(): HasManyThrough
    {
        return $this->hasManyThrough(BookingActivities::class, Booking::class, 'id', 'booking_id', 'booking_id');
    }

    public function flights(): HasManyThrough
    {
        return $this->hasManyThrough(BookingFlight::class, Booking::class, 'id', 'booking_id', 'booking_id');
    }

    public function transport(): HasManyThrough
    {
        return $this->hasManyThrough(BookingTransport::class, Booking::class, 'id', 'booking_id', 'booking_id');
    }

    public function merchandise(): HasManyThrough
    {
        return $this->hasManyThrough(BookingMerchandise::class, Booking::class, 'id', 'booking_id', 'booking_id');
    }

    public function getIsSingleOccupantAttribute(): bool
    {
        foreach ($this->accommodation as $bookingAccommodation) {
            if ($bookingAccommodation->roomType->maximum_occupancy == 1) return true;
            $query = \DB::table('booking_accommodations')
                ->where('group_id', '=', $bookingAccommodation->group_id)
                ->where('accommodation_inventory_tour_id', '=', $bookingAccommodation->accommodation_inventory_tour_id)
                ->where('booking_id', '=', $this->booking_id)
                ->select('id');
            if ($query->count() == 1) return true;
        }
        return false;
    }
}
