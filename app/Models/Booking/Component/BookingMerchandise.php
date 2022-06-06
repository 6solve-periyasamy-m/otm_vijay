<?php

namespace App\Models\Booking\Component;

use App\Models\Booking\BookingTraveller;
use App\Models\Tour\Merchandise;
use App\Repository\Model\Booking\Component\BookingMerchandiseRepository;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Booking\BookingMerchandise
 *
 * @property-read Merchandise|null $tourComponent
 * @property-read BookingTraveller $traveller
 * @property-read BookingMerchandiseRepository $repository
 * @method static Builder|BookingMerchandise newModelQuery()
 * @method static Builder|BookingMerchandise newQuery()
 * @method static Builder|BookingMerchandise query()
 * @mixin Eloquent
 */
class BookingMerchandise extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'merchandise_id', 'booking_id'];

    public function traveller(): BelongsTo
    {
        return $this->belongsTo(BookingTraveller::class, 'booking_traveller_id');
    }

    public function tourComponent(): BelongsTo
    {
        return $this->belongsTo(Merchandise::class, 'merchandise_id');
    }

    public function getRepositoryAttribute(): BookingMerchandiseRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new BookingMerchandiseRepository($this);
        return $this->internal_repository;
    }
}
