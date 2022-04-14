<?php

namespace App\Models;

use App\Models\Customer\Customer;
use App\Models\Transport\TransportInventoryTour;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\BookingTransport
 *
 * @property int $id
 * @property int $booking_id
 * @property int $customer_id
 * @property int $transport_inventory_tour_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Booking $booking
 * @property-read Customer|null $customer
 * @property-read TransportInventoryTour $tourComponent
 * @method static Builder|BookingTransport newModelQuery()
 * @method static Builder|BookingTransport newQuery()
 * @method static Builder|BookingTransport query()
 * @method static Builder|BookingTransport whereBookingId($value)
 * @method static Builder|BookingTransport whereCreatedAt($value)
 * @method static Builder|BookingTransport whereCustomerId($value)
 * @method static Builder|BookingTransport whereId($value)
 * @method static Builder|BookingTransport whereTransportInventoryTourId($value)
 * @method static Builder|BookingTransport whereUpdatedAt($value)
 * @mixin Eloquent
 */
class BookingTransport extends Model
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
        return $this->belongsTo(TransportInventoryTour::class, 'transport_inventory_tour_id');
    }
}
