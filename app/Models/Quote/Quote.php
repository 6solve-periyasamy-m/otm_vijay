<?php

namespace App\Models\Quote;

use App\Models\Helper\QuoteStatus;
use App\Models\Order\Order;
use App\Models\Tour\Tour;
use App\Repository\Model\Quote\QuoteRepository;
use Database\Factories\Quote\QuoteFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Quote\Quote
 *
 * @property int $id
 * @property int $tour_id
 * @property int|null $order_id
 * @property int|null $lead_traveller_id
 * @property int|null $default_traveller_id
 * @property string|null $reference
 * @property double|null $deposit
 * @property Carbon|null $expires
 * @property QuoteStatus $quote_status
 * @property string|null $internal_notes
 * @property string|null $external_notes
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read QuoteRepository $repository
 * @property-read Collection|QuoteInstallment[] $installments
 * @property-read int|null $installments_count
 * @property-read Order|null $order
 * @property-read QuoteStatus $status
 * @property-read Collection|QuotePricePoint[] $pricePoints
 * @property-read int|null $price_points_count
 * @property-read Tour $tour
 * @method static Builder|Quote whereReference($value)
 * @property-read QuoteTraveller|null $defaultTraveller
 * @property-read QuoteTraveller|null $leadTraveller
 * @property-read Collection|QuoteTraveller[] $travellers
 * @property-read int|null $travellers_count
 * @method static QuoteFactory factory(...$parameters)
 * @method static Builder|Quote newModelQuery()
 * @method static Builder|Quote newQuery()
 * @method static QueryBuilder|Quote onlyTrashed()
 * @method static Builder|Quote query()
 * @method static Builder|Quote whereCreatedAt($value)
 * @method static Builder|Quote whereDefaultTravellerId($value)
 * @method static Builder|Quote whereDeletedAt($value)
 * @method static Builder|Quote whereDeposit($value)
 * @method static Builder|Quote whereExpires($value)
 * @method static Builder|Quote whereExternalNotes($value)
 * @method static Builder|Quote whereId($value)
 * @method static Builder|Quote whereInternalNotes($value)
 * @method static Builder|Quote whereLeadTravellerId($value)
 * @method static Builder|Quote whereOrderId($value)
 * @method static Builder|Quote whereTourId($value)
 * @method static Builder|Quote whereUpdatedAt($value)
 * @method static QueryBuilder|Quote withTrashed()
 * @method static QueryBuilder|Quote withoutTrashed()
 * @mixin Eloquent

 */
class Quote extends Model
{
    use HasFactory, SoftDeletes;

    private QuoteRepository $internal_repository;

    protected $guarded = [];
    protected $casts = [
        'deposit' => 'double',
        'expires' => 'datetime',
        'quote_status' => QuoteStatus::class
    ];

    public function leadTraveller(): BelongsTo
    {
        return $this->belongsTo(QuoteTraveller::class, 'lead_traveller_id');
    }

    public function defaultTraveller(): BelongsTo
    {
        return $this->belongsTo(QuoteTraveller::class, 'default_traveller_id');
    }

    public function travellers(): HasMany
    {
        return $this->hasMany(QuoteTraveller::class);
    }

    public function pricePoints(): HasMany
    {
        return $this->hasMany(QuotePricePoint::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(QuoteInstallment::class);
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getStatusAttribute(): QuoteStatus
    {
        if (isset($this->order_id)) {
            return QuoteStatus::CONVERTED;
        }
        $expired = now()->isAfter($this->expires);
        // QuoteStatus -1, 0 and 1 should be overwritten by expired, but the others should not
        return $this->quote_status->value < 2 && $expired ? QuoteStatus::EXPIRED : $this->quote_status;
    }

    public function getRepositoryAttribute(): QuoteRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new QuoteRepository($this);
        return $this->internal_repository;
    }
}
