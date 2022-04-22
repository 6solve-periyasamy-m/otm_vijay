<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingMerchandise extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'merchandise_id', 'booking_id'];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function tourComponent(): BelongsTo
    {
        return $this->belongsTo(Merchandise::class, 'merchandise_id');
    }
}
