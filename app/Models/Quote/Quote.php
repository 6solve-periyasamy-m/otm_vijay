<?php

namespace App\Models\Quote;

use App\Models\Customer\Agent;
use App\Models\Customer\Organization;
use App\Models\Helper\Enum\QuoteStatus;
use App\Models\Helper\Model;
use App\Models\Helper\Traits\HasAdditionalCosts;
use App\Models\Location\Currency;
use App\Models\Order\Order;
use App\Models\Quote\Component\QuoteAccommodation;
use App\Models\Quote\Component\QuoteActivity;
use App\Models\Quote\Component\QuoteFlight;
use App\Models\Quote\Component\QuoteMerchandise;
use App\Models\Quote\Component\QuoteTransport;
use App\Models\System\Brand;
use App\Models\System\TaxBracket;
use App\Models\Tour\Event;
use App\Models\Tour\Tour;
use App\Models\User;
use App\Repository\Model\Quote\QuoteRepository;
use Database\Factories\Quote\QuoteFactory;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
 * @property int|null $tax_bracket_id
 * @property int|null $organization_id
 * @property int|null $lead_traveller_id
 * @property int|null $consultant_id
 * @property int|null $currency_id
 * @property int|null $event_id
 * @property int|null $brand_id
 * @property int|null $agent_id
 * @property int $revision
 * @property string|null $reference
 * @property string $name
 * @property string|null $description
 * @property float|null $deposit
 * @property float|null $commission
 * @property bool $is_deposit_percentage
 * @property float $single_occupancy_surcharge
 * @property Carbon $final_payment
 * @property Carbon $date_from
 * @property Carbon $date_to
 * @property string $terms
 * @property float|null $from_rate
 * @property float|null $to_rate
 * @property string $invoice_footer
 * @property int $paying Cached paying value
 * @property int $travelling Cached travelling value
 * @property string|null $payment_details Details for sending payment information. *Do not use*
 * @property Carbon|null $expires
 * @property QuoteStatus $quote_status
 * @property string|null $internal_notes
 * @property string|null $external_notes
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string $ref Reference-Revision
 * @property-read Collection|QuoteAccommodation[] $accommodation
 * @property-read Collection|QuoteProspect[] $travellers
 * @property-read Collection|QuoteSection[] $sections
 * @property-read Brand|null $linkedBrand
 * @property-read User|null $consultant
 * @property-read Agent|null $agent
 * @property-read Brand $brand
 * @property-read Currency|null $currency
 * @property-read Organization|null $organization
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
 * @property-read Collection|SentQuote[] $sentQuotes
 * @property-read int|null $sentQuotes_count
 * @property-read Tour|null $tour
 * @property-read Collection|QuoteTransport[] $transport
 * @property-read int|null $transport_count
 * @property-read float $remaining
 * @property-read string $makePaymentDetails Details for documents to include about making a payment
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
 * @method static Builder|Quote whereDescription($value)
 * @method static Builder|Quote whereEventId($value)
 * @method static Builder|Quote whereExpires($value)
 * @method static Builder|Quote whereExternalNotes($value)
 * @method static Builder|Quote whereFinalPayment($value)
 * @method static Builder|Quote whereId($value)
 * @method static Builder|Quote whereInternalNotes($value)
 * @method static Builder|Quote whereInvoiceFooter($value)
 * @method static Builder|Quote whereLeadTravellerId($value)
 * @method static Builder|Quote whereName($value)
 * @method static Builder|Quote whereOrderId($value)
 * @method static Builder|Quote whereQuoteStatus($value)
 * @method static Builder|Quote whereReference($value)
 * @method static Builder|Quote whereSingleOccupancySurcharge($value)
 * @method static Builder|Quote whereTerms($value)
 * @method static Builder|Quote whereTourId($value)
 * @method static Builder|Quote whereUpdatedAt($value)
 * @method static QueryBuilder|Quote withTrashed()
 * @method static QueryBuilder|Quote withoutTrashed()
 * @mixin Eloquent
 */
class Quote extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasAdditionalCosts;

    protected $guarded = [];
    protected $casts = [
        'is_deposit_percentage' => 'bool',
        'expires' => 'date:Y-m-d',
        'date_from' => 'date:Y-m-d',
        'date_to' => 'date:Y-m-d',
        'final_payment' => 'date:Y-m-d',
        'sent' => 'datetime',
        'quote_status' => QuoteStatus::class,
        'from_rate' => 'float',
        'to_rate' => 'float',
    ];
    private QuoteRepository $internal_repository;
    protected array $cascadeDeletes = ['sentQuotes', 'leadTraveller', 'pricePoints', 'installments', 'accommodation', 'activities', 'flights', 'transport', 'merchandise', 'costs'];

    public function sentQuotes(): HasMany
    {
        return $this->hasMany(SentQuote::class, 'quote_id')->orderBy('sent', 'desc');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(QuoteSection::class, 'quote_id')->orderBy('order');
    }

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'consultant_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function bracket(): BelongsTo
    {
        return $this->belongsTo(TaxBracket::class, 'tax_bracket_id');
    }

    public function taxBracket(): TaxBracket|null
    {
        $event = is_array($this->event) ? new Event($this->event) : $this->event;
        $bracket = $this->bracket ?? $event?->taxBracket() ?? $this->brand?->taxBracket();
        if ($bracket === null || $bracket instanceof TaxBracket) {
            return $bracket;
        }
        if (is_array($bracket)) {
            return new TaxBracket($bracket);
        }
        return null;
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    public function leadTraveller(): BelongsTo
    {
        return $this->belongsTo(QuoteProspect::class, 'lead_traveller_id');
    }

    public function travellers(): HasMany
    {
        return $this->hasMany(QuoteProspect::class, 'quote_id');
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

    public function linkedBrand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
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

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function getStatusAttribute(): QuoteStatus
    {
        // If you need to get the Quote Status using raw SQL. use this IF statement
        // (IF(quotes.order_id IS NULL, IF(NOW() < quotes.expires, quotes.quote_status, IF(quotes.quote_status < 2, -1, quotes.quote_status)), 4))
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

    public function getBrandAttribute(): Brand
    {
        return $this->linkedBrand ?? Brand::getSystemBrand();
    }

    public function setBrandAttribute(Brand $brand)
    {
        $this->brand_id = $brand->id;
        $this->save();
    }

    public function getRefAttribute(): string
    {
        return $this->reference . '-' . $this->revision;
    }

    public function getRemainingAttribute(): float
    {
        return $this->repository->getRemainingInstallment();
    }

    public function getRemainingPercentage(): float
    {
        $price = $this->repository->getTotalCost(1);
        return empty($price) ? 0 : sigfig(($this->remaining / $price) * 100);
    }

    public function getDepositAmount(int $count = 1): float|null
    {
        $price = ($this->repository->getTotalCost($count) / $count);
        return min($this->repository->getTotalCost($count), ($this->is_deposit_percentage ? sigfig(($price * ($this->deposit/100))) : $this->deposit) * $count);
    }

    public function getDepositPercentage(int $count = 1): float|null
    {
        $price = $this->repository->getTotalCost($count);
        if (empty($price) && !$this->is_deposit_percentage) { return 0; }
        return $this->is_deposit_percentage ? $this->deposit : (sigfig(($this->deposit / $price) * 100));
    }

    public function getMakePaymentDetailsAttribute(): string
    {
        if (empty($this->payment_details)) {
            return setting('company.bank_transfer', "");
        }
        return $this->payment_details;
    }
}
