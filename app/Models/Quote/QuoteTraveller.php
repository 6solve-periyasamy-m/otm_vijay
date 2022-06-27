<?php

namespace App\Models\Quote;

use App\Models\Quote\Component\QuoteAccommodation;
use App\Models\Quote\Component\QuoteActivity;
use App\Models\Quote\Component\QuoteFlight;
use App\Models\Quote\Component\QuoteMerchandise;
use App\Models\Quote\Component\QuoteTransport;
use App\Repository\Model\Quote\QuoteTravellerRepository;
use Database\Factories\Quote\QuoteTravellerFactory;
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
 * App\Models\Quote\QuoteTraveller
 *
 * @property int $id
 * @property int|null $quote_prospect_id
 * @property int $quote_id
 * @property bool $has_cost
 * @property bool $is_travelling
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string $name
 * @property-read string $email
 * @property-read QuoteTravellerRepository $repository
 * @property-read QuoteProspect|null $prospect
 * @property-read Quote $quote
 * @property-read Collection|QuoteAccommodation[] $accommodation
 * @property-read int|null $accommodation_count
 * @property-read Collection|QuoteActivity[] $activities
 * @property-read int|null $activities_count
 * @property-read Collection|QuoteFlight[] $flights
 * @property-read int|null $flights_count
 * @property-read Collection|QuoteMerchandise[] $merchandise
 * @property-read int|null $merchandise_count
 * @property-read Collection|QuoteTransport[] $transport
 * @property-read int|null $transport_count
 * @method static QuoteTravellerFactory factory(...$parameters)
 * @method static Builder|QuoteTraveller newModelQuery()
 * @method static Builder|QuoteTraveller newQuery()
 * @method static QueryBuilder|QuoteTraveller onlyTrashed()
 * @method static Builder|QuoteTraveller query()
 * @method static Builder|QuoteTraveller whereCreatedAt($value)
 * @method static Builder|QuoteTraveller whereDeletedAt($value)
 * @method static Builder|QuoteTraveller whereHasCost($value)
 * @method static Builder|QuoteTraveller whereId($value)
 * @method static Builder|QuoteTraveller whereIsTravelling($value)
 * @method static Builder|QuoteTraveller whereQuoteId($value)
 * @method static Builder|QuoteTraveller whereQuoteProspectId($value)
 * @method static Builder|QuoteTraveller whereUpdatedAt($value)
 * @method static QueryBuilder|QuoteTraveller withTrashed()
 * @method static QueryBuilder|QuoteTraveller withoutTrashed()
 * @mixin Eloquent
 */
class QuoteTraveller extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $casts = [
        'has_cost' => 'boolean',
        'is_travelling' => 'boolean'
    ];

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function prospect(): BelongsTo
    {
        return $this->belongsTo(QuoteProspect::class);
    }

    public function accommodation(): HasMany
    {
        return $this->hasMany(QuoteAccommodation::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(QuoteActivity::class);
    }

    public function flights(): HasMany
    {
        return $this->hasMany(QuoteFlight::class);
    }

    public function transport(): HasMany
    {
        return $this->hasMany(QuoteTransport::class);
    }

    public function merchandise(): HasMany
    {
        return $this->hasMany(QuoteMerchandise::class);
    }

    public function getNameAttribute(): string
    {
        if (isset($this->prospect)) {
            return $this->prospect->name;
        }
        return trans('quotes.traveller.prospect.unset');
    }

    public function getEmailAttribute(): string
    {
        if (isset($this->prospect)) {
            $source = $this->prospect->customer ?? $this->prospect;
            return $source->email_address ?? trans('quotes.traveller.prospect.unset');
        }
        return trans('quotes.traveller.prospect.unset');
    }

    public function getRepositoryAttribute(): QuoteTravellerRepository
    {
        if (!isset($this->interal_repository)) $this->internal_repository = new QuoteTravellerRepository($this);
        return $this->interal_repository;
    }
}
