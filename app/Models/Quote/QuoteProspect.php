<?php

namespace App\Models\Quote;

use App\Models\Customer\Customer;
use App\Models\Helper\Model;
use Database\Factories\Quote\QuoteProspectFactory;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Quote\QuoteProspect
 *
 * @property int $id
 * @property int|null $customer_id
 * @property int|null $quote_id
 * @property bool $paying
 * @property bool $travelling
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Customer $customer
 * @property-read string $email
 * @property-read string $name
 * @property-read string $phone
 * @property-read Quote|null $quote
 * @property-read bool $is_lead
 * @method static QuoteProspectFactory factory(...$parameters)
 * @method static Builder|QuoteProspect newModelQuery()
 * @method static Builder|QuoteProspect newQuery()
 * @method static QueryBuilder|QuoteProspect onlyTrashed()
 * @method static Builder|QuoteProspect query()
 * @method static Builder|QuoteProspect whereCreatedAt($value)
 * @method static Builder|QuoteProspect whereCustomerId($value)
 * @method static Builder|QuoteProspect whereDeletedAt($value)
 * @method static Builder|QuoteProspect whereId($value)
 * @method static Builder|QuoteProspect wherePaying($value)
 * @method static Builder|QuoteProspect whereTravelling($value)
 * @method static Builder|QuoteProspect whereUpdatedAt($value)
 * @method static QueryBuilder|QuoteProspect withTrashed()
 * @method static QueryBuilder|QuoteProspect withoutTrashed()
 * @mixin Eloquent
 */
class QuoteProspect extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $guarded = [];
    protected $casts = ['paying' => 'boolean', 'travelling' => 'boolean', 'lead_traveller' => 'boolean'];

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class, 'quote_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function getNameAttribute(): string
    {
        return isset($this->customer) ? "{$this->customer->title} {$this->customer->first_name} {$this->customer->last_name}" : "Customer Not Found";
    }

    public function getEmailAttribute(): string
    {
        return $this->customer?->email_address ?? "";
    }

    public function getPhoneAttribute(): string
    {
        return $this->customer?->mobile_number ?? "";
    }

    public function getIsLeadAttribute(): bool
    {
        return $this->id === $this->quote->lead_traveller_id;
    }
}
