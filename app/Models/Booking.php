<?php

namespace App\Models;

use App\Models\Tour;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function accommodation()
    {
        return $this->hasMany(BookingAccommodation::class, 'booking_id');
    }

    public function activities()
    {
        return $this->hasMany(BookingActivities::class, 'booking_id');
    }

    public function flights()
    {
        return $this->hasMany(BookingFlight::class, 'booking_id');
    }

    public function transports()
    {
        return $this->hasMany(BookingTransport::class, 'booking_id');
    }

    public function travellers()
    {
        return $this->hasMany(BookingTraveller::class, 'booking_id');
    }
}
