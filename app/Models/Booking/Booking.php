<?php

namespace App\Models\Booking;

use App\Models\Booking\Component\BookingAccommodation;
use App\Models\Booking\Component\BookingActivity;
use App\Models\Booking\Component\BookingFlight;
use App\Models\Booking\Component\BookingMerchandise;
use App\Models\Booking\Component\BookingTransport;
use App\Models\Helper\Model;
use App\Models\System\FellohLink;
use App\Models\Tour\Tour;
use App\Models\Voucher\VoucherCode;
use App\Repository\Model\Booking\BookingRepository;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships as HasDeepRelations;

/**
 * App\Models\Booking\Booking
 *
 * @property int $id
 * @property int $tour_id
 * @property int|null $lead_traveller_id
 * @property int|null $booking_accommodation_id
 * @property int|null $order_id
 * @property string|null $token
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read BookingTraveller|null $leadTraveller
 * @property-read Tour $tour
 * @property-read Collection|BookingGroup[] $groups
 * @property-read Collection|VoucherCode[] $vouchers
 * @property-read int|null $groups_count
 * @property-read Collection|BookingTraveller[] $travellers
 * @property-read Collection|BookingTraveller[] $additionalTravellers Travellers excluding lead traveller
 * @property-read Collection|BookingAccommodation[] $accommodation
 * @property-read Collection|BookingActivity[] $activities
 * @property-read Collection|BookingFlight[] $flights
 * @property-read Collection|BookingTransport[] $transport
 * @property-read Collection|BookingMerchandise[] $merchandise
 * @property-read int|null $travellers_count
 * @property-read int $traveller_count
 * @property-read float $total_cost
 * @property-read float $deposit
 * @property-read float $due_today
 * @property-read BookingRepository $repository
 * @property-read FellohLink|null $felloh
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
    use HasDeepRelations;

    protected $guarded = [];
    private BookingRepository $internal_repository;

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function vouchers(): HasManyDeep
    {
        return $this->hasManyDeep(VoucherCode::class, [BookingTraveller::class, 'voucher_bookings']);
    }

    public function leadTraveller(): BelongsTo
    {
        return $this->belongsTo(BookingTraveller::class, 'lead_traveller_id');
    }

    public function groups(): HasMany
    {
        return $this->hasMany(BookingGroup::class, 'booking_id');
    }

    public function accommodation(): HasManyThrough
    {
        return $this->hasManyThrough(BookingAccommodation::class, BookingGroup::class, 'booking_id', 'booking_group_id');
    }

    public function activities(): HasManyThrough
    {
        return $this->hasManyThrough(BookingActivity::class, BookingTraveller::class, 'booking_id', 'booking_traveller_id');
    }

    public function flights(): HasManyThrough
    {
        return $this->hasManyThrough(BookingFlight::class, BookingTraveller::class, 'booking_id', 'booking_traveller_id');
    }

    public function transport(): HasManyThrough
    {
        return $this->hasManyThrough(BookingTransport::class, BookingTraveller::class, 'booking_id', 'booking_traveller_id');
    }

    public function merchandise(): HasManyThrough
    {
        return $this->hasManyThrough(BookingMerchandise::class, BookingTraveller::class, 'booking_id', 'booking_traveller_id');
    }

    public function felloh(): MorphOne
    {
        return $this->morphOne(FellohLink::class, 'order');
    }

    public function getRepositoryAttribute(): BookingRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new BookingRepository($this);
        return $this->internal_repository;
    }

    public function getTotalCostAttribute(): float
    {
        return $this->repository->getTotalCost();
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
        return $this->tour?->deposit_amount;
    }

    public function getDueTodayAttribute(): float
    {
        return $this->repository->getDueTodayAmount();
    }
}
