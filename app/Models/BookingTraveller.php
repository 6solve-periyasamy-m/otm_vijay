<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function accommodation(): HasMany
    {
        return $this->hasMany(BookingAccommodation::class, 'customer_id', 'customer_id')->where('booking_id', $this->booking_id);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(BookingActivities::class, 'customer_id', 'customer_id')->where('booking_id', $this->booking_id);
    }

    public function flights(): HasMany
    {
        return $this->hasMany(BookingFlight::class, 'customer_id', 'customer_id')->where('booking_id', $this->booking_id);
    }

    public function transport(): HasMany
    {
        return $this->hasMany(BookingTransport::class, 'customer_id', 'customer_id')->where('booking_id', $this->booking_id);
    }

    public function merchandise(): HasMany
    {
        return $this->hasMany(BookingMerchandise::class, 'customer_id', 'customer_id')->where('booking_id', $this->booking_id);
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

    public function getRoomTypeAttribute(): ?RoomType
    {
        return $this->accommodation()->first()?->roomType;
    }

    public function getGroupAttribute(): ?RoomType
    {
        return $this->accommodation()->first()?->group;
    }
}
