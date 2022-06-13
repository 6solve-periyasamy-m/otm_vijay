<?php

namespace App\Models\Booking\Component;

use App\Models\Booking\BookingTraveller;
use App\Models\Transport\TransportInventoryTour;
use App\Repository\Model\Booking\Component\BookingTransportRepository;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Booking\BookingTransport
 *
 * @property int $id
 * @property int $booking_traveller_id
 * @property int $transport_inventory_tour_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read TransportInventoryTour $tourComponent
 * @property-read BookingTraveller $traveller
 * @property-read BookingTransportRepository $repository
 * @method static Builder|BookingTransport newModelQuery()
 * @method static Builder|BookingTransport newQuery()
 * @method static Builder|BookingTransport query()
 * @method static Builder|BookingTransport whereBookingTravellerId($value)
 * @method static Builder|BookingTransport whereCreatedAt($value)
 * @method static Builder|BookingTransport whereId($value)
 * @method static Builder|BookingTransport whereTransportInventoryTourId($value)
 * @method static Builder|BookingTransport whereUpdatedAt($value)
 * @mixin Eloquent
 */
class BookingTransport extends Model
{
    use HasFactory;

    protected $guarded = [];

    private BookingTransportRepository $internal_repository;

    public function traveller(): BelongsTo
    {
        return $this->belongsTo(BookingTraveller::class, 'booking_traveller_id');
    }

    public function tourComponent(): BelongsTo
    {
        return $this->belongsTo(TransportInventoryTour::class, 'transport_inventory_tour_id');
    }

    public static function compare(BookingTransport $a, BookingTransport $b): int
    {
        $aStart = $a->tourComponent->inventory->departs_at;
        $bStart = $b->tourComponent->inventory->departs_at;
        if ($aStart->gt($bStart)) return 1;
        if ($aStart->lt($bStart)) return -1;
        return 0;
    }

    public function getRepositoryAttribute(): BookingTransportRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new BookingTransportRepository($this);
        return $this->internal_repository;
    }
}
