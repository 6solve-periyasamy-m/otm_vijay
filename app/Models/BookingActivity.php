<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingActivity extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'activity_inventory_tour_id', 'booking_id'];

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
        return $this->belongsTo(ActivityInventoryTour::class, 'activity_inventory_tour_id');
    }

    public static function compare(BookingActivity $a, BookingActivity $b): int
    {
        $aStart = $a->tourComponent->inventory->starts_at;
        $bStart = $b->tourComponent->inventory->starts_at;
        if ($aStart->gt($bStart)) return 1;
        if ($aStart->lt($bStart)) return -1;
        return 0;
    }
}
