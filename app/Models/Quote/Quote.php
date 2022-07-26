<?php

namespace App\Models\Quote;

use App\Models\Helper\QuoteStatus;
use App\Models\Order\Order;
use App\Models\Quote\Component\QuoteAccommodation;
use App\Models\Quote\Component\QuoteActivity;
use App\Models\Quote\Component\QuoteFlight;
use App\Models\Quote\Component\QuoteMerchandise;
use App\Models\Quote\Component\QuoteTransport;
use App\Models\Tour\Event;
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
 * @property int|null $tour_id
 * @property int|null $order_id
 * @property int|null $lead_traveller_id
 * @property int|null $event_id
 * @property string|null $reference
 * @property float|null $deposit
 * @property float $single_occupancy_surcharge
 * @property bool $locked
 * @property Carbon $final_payment
 * @property Carbon $date_from
 * @property Carbon $date_to
 * @property string $terms
 * @property string $invoice_footer
 * @property Carbon|null $expires
 * @property Carbon|null $sent
 * @property QuoteStatus $quote_status
 * @property string|null $internal_notes
 * @property string|null $external_notes
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|QuoteAccommodation[] $accommodation
 * @property-read int|null $accommodation_count
 * @property-read Collection|QuoteActivity[] $activities
 * @property-read int|null $activities_count
 * @property-read Event|null $event
 * @property-read Collection|QuoteFlight[] $flights
 * @property-read int|null $flights_count
 * @property-read QuoteRepository $repository
 * @property-read QuoteStatus $status
 * @property-read Collection|QuoteInstallment[] $installments
 * @property-read int|null $installments_count
 * @property-read QuoteProspect|null $leadTraveller
 * @property-read Collection|QuoteMerchandise[] $merchandise
 * @property-read int|null $merchandise_count
 * @property-read Order|null $order
 * @property-read Collection|QuotePricePoint[] $pricePoints
 * @property-read int|null $price_points_count
 * @property-read Tour|null $tour
 * @property-read Collection|QuoteTransport[] $transport
 * @property-read int|null $transport_count
 * @method static QuoteFactory factory(...$parameters)
 * @method static Builder|Quote newModelQuery()
 * @method static Builder|Quote newQuery()
 * @method static QueryBuilder|Quote onlyTrashed()
 * @method static Builder|Quote query()
 * @method static Builder|Quote whereCreatedAt($value)
 * @method static Builder|Quote whereDateFrom($value)
 * @method static Builder|Quote whereDateTo($value)
 * @method static Builder|Quote whereDeletedAt($value)
 * @method static Builder|Quote whereDeposit($value)
 * @method static Builder|Quote whereEventId($value)
 * @method static Builder|Quote whereExpires($value)
 * @method static Builder|Quote whereExternalNotes($value)
 * @method static Builder|Quote whereFinalPayment($value)
 * @method static Builder|Quote whereId($value)
 * @method static Builder|Quote whereInternalNotes($value)
 * @method static Builder|Quote whereInvoiceFooter($value)
 * @method static Builder|Quote whereLeadTravellerId($value)
 * @method static Builder|Quote whereLocked($value)
 * @method static Builder|Quote whereOrderId($value)
 * @method static Builder|Quote whereQuoteStatus($value)
 * @method static Builder|Quote whereReference($value)
 * @method static Builder|Quote whereTerms($value)
 * @method static Builder|Quote whereTourId($value)
 * @method static Builder|Quote whereUpdatedAt($value)
 * @method static QueryBuilder|Quote withTrashed()
 * @method static QueryBuilder|Quote withoutTrashed()
 * @mixin Eloquent
 */
class Quote extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $casts = [
        'deposit' => 'double',
        'single_occupancy_surcharge' => 'double',
        'locked' => 'boolean',
        'expires' => 'datetime',
        'date_from' => 'date',
        'date_to' => 'date',
        'sent' => 'datetime',
        'quote_status' => QuoteStatus::class
    ];
    private QuoteRepository $internal_repository;

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function leadTraveller(): BelongsTo
    {
        return $this->belongsTo(QuoteProspect::class, 'lead_traveller_id');
    }

    public function pricePoints(): HasMany
    {
        return $this->hasMany(QuotePricePoint::class, 'quote_id')->orderBy('quantity');
    }

    public function installments(): HasMany
    {
        return $this->hasMany(QuoteInstallment::class)->orderBy('due_on');
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function accommodation(): HasMany
    {
        return $this->hasMany(QuoteAccommodation::class, 'quote_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(QuoteActivity::class, 'quote_id');
    }

    public function flights(): HasMany
    {
        return $this->hasMany(QuoteFlight::class, 'quote_id');
    }

    public function transport(): HasMany
    {
        return $this->hasMany(QuoteTransport::class, 'quote_id');
    }

    public function merchandise(): HasMany
    {
        return $this->hasMany(QuoteMerchandise::class, 'quote_id');
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
