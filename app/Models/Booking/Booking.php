<?php

namespace App\Models\Booking;

use App\Models\Tour\Tour;
use App\Repository\Model\Booking\BookingRepository;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Booking\Booking
 *
 * @property int $id
 * @property int $tour_id
 * @property int|null $lead_traveller_id
 * @property int|null $order_id
 * @property string|null $token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read BookingTraveller|null $leadTraveller
 * @property-read Tour $tour
 * @property-read Collection|BookingGroup[] $groups
 * @property-read int|null $groups_count
 * @property-read Collection|BookingTraveller[] $travellers
 * @property-read Collection|BookingTraveller[] $additionalTravellers Travellers excluding lead traveller
 * @property-read int|null $travellers_count
 * @property-read int $traveller_count
 * @property-read float $total_cost
 * @property-read float $deposit
 * @property-read BookingRepository $repository
 * @method static Builder|Booking newModelQuery()
 * @method static Builder|Booking newQuery()
 * @method static Builder|Booking query()
 * @method static Builder|Booking whereCreatedAt($value)
 * @method static Builder|Booking whereDeletedAt($value)
 * @method static Builder|Booking whereId($value)
 * @method static Builder|Booking whereLeadTravellerId($value)
 * @method static Builder|Booking whereOrderId($value)
 * @method static Builder|Booking whereToken($value)
 * @method static Builder|Booking whereTourId($value)
 * @method static Builder|Booking whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Booking extends Model
{
    protected $guarded = [];
    private BookingRepository $internal_repository;

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function leadTraveller(): BelongsTo
    {
        return $this->belongsTo(BookingTraveller::class, 'lead_traveller_id');
    }

    public function groups(): HasMany
    {
        return $this->hasMany(BookingGroup::class, 'booking_id');
    }

    public function getRepositoryAttribute(): BookingRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new BookingRepository($this);
        return $this->internal_repository;
    }

    public function getTotalCostAttribute(): float
    {
        return $this->tour->base_price_per_person * $this->traveller_count;
    }

    public function getTravellerCountAttribute(): int
    {
        return $this->travellers()->count();
    }

    public function travellers(): HasMany
    {
        return $this->hasMany(BookingTraveller::class, 'booking_id');
    }

    public function additionalTravellers(): HasMany
    {
        return $this->travellers()->whereNot('id', '=', $this->lead_traveller_id);
    }

    public function getDepositAttribute(): float
    {
        return $this->tour->deposit;
    }
}
