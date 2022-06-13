<?php

namespace App\Models\Booking;

use App\Models\Booking\Component\BookingAccommodation;
use App\Repository\Model\Booking\BookingGroupRepository;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Booking\BookingGroup
 *
 * @property int $id
 * @property string $name
 * @property int $booking_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Booking $booking
 * @property-read Collection|BookingAccommodation[] $accommodation
 * @property-read int|null $accommodation_count
 * @property-read Collection|BookingTraveller[] $travellers
 * @property-read int|null $travellers_count
 * @property-read BookingGroupRepository $repository
 * @method static Builder|BookingGroup newModelQuery()
 * @method static Builder|BookingGroup newQuery()
 * @method static Builder|BookingGroup query()
 * @method static Builder|BookingGroup whereCreatedAt($value)
 * @method static Builder|BookingGroup whereId($value)
 * @method static Builder|BookingGroup whereName($value)
 * @method static Builder|BookingGroup whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|BookingGroup whereBookingId($value)
 */
class BookingGroup extends Model
{
    use HasFactory;

    private BookingGroupRepository $internal_repository;

    protected $guarded = [];

    public function travellers(): BelongsToMany
    {
        return $this->belongsToMany(BookingTraveller::class, BookingTravellerGroup::class)->using(BookingTravellerGroup::class);
    }

    public function accommodation(): HasMany
    {
        return $this->hasMany(BookingAccommodation::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function getRepositoryAttribute(): BookingGroupRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new BookingGroupRepository($this);
        return $this->internal_repository;
    }
}
