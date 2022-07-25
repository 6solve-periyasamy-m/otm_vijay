<?php

namespace App\Models\Quote;

use App\Models\Customer\Customer;
use App\Models\Location\Address;
use Database\Factories\Quote\QuoteProspectFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Quote\QuoteProspect
 *
 * @property int $id
 * @property int|null $customer_id
 * @property string|null $title
 * @property string|null $first_name
 * @property string|null $middle_names
 * @property string|null $last_name
 * @property string|null $date_of_birth
 * @property string|null $mobile_number
 * @property string|null $email_address
 * @property int|null $home_address_id
 * @property int|null $billing_address_id
 * @property bool $paying
 * @property bool $travelling
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string $name
 * @property-read string $email
 * @property-read string $phone
 * @property-read Address|null $billingAddress
 * @property-read Customer|null $customer
 * @property-read Address|null $homeAddress
 * @property-read Quote|null $quote
 * @method static QuoteProspectFactory factory(...$parameters)
 * @method static Builder|QuoteProspect newModelQuery()
 * @method static Builder|QuoteProspect newQuery()
 * @method static QueryBuilder|QuoteProspect onlyTrashed()
 * @method static Builder|QuoteProspect query()
 * @method static Builder|QuoteProspect whereBillingAddressId($value)
 * @method static Builder|QuoteProspect whereCreatedAt($value)
 * @method static Builder|QuoteProspect whereCustomerId($value)
 * @method static Builder|QuoteProspect whereDateOfBirth($value)
 * @method static Builder|QuoteProspect whereDeletedAt($value)
 * @method static Builder|QuoteProspect whereEmailAddress($value)
 * @method static Builder|QuoteProspect whereFirstName($value)
 * @method static Builder|QuoteProspect whereHomeAddressId($value)
 * @method static Builder|QuoteProspect whereId($value)
 * @method static Builder|QuoteProspect whereLastName($value)
 * @method static Builder|QuoteProspect whereMiddleNames($value)
 * @method static Builder|QuoteProspect whereMobileNumber($value)
 * @method static Builder|QuoteProspect whereTitle($value)
 * @method static Builder|QuoteProspect whereUpdatedAt($value)
 * @method static QueryBuilder|QuoteProspect withTrashed()
 * @method static QueryBuilder|QuoteProspect withoutTrashed()
 * @mixin Eloquent
 */
class QuoteProspect extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $casts = ['paying' => 'boolean', 'travelling' => 'boolean'];

    public function quote(): HasOne
    {
        return $this->hasOne(Quote::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function homeAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'home_address_id');
    }

    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function getNameAttribute(): string
    {
        $source = $this->customer ?? $this;
        return "{$source->title} {$source->first_name} {$source->last_name}";
    }

    public function getEmailAttribute(): string
    {
        $source = $this->customer ?? $this;
        return "{$source->email_address}";
    }

    public function getPhoneAttribute(): string
    {
        $source = $this->customer ?? $this;
        return "{$source->mobile_number}";
    }
}
