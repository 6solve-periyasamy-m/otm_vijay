<?php

namespace App\Models\Customer;

use App\Models\Location\Address;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Models\Quote\Quote;
use App\Models\Quote\QuoteProspect;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

/**
 * App\Models\Customer\Organization
 *
 * @property int $id
 * @property string $name
 * @property int $delivery_address_id
 * @property int $billing_address_id
 * @property float|null $commission
 * @property string|null $contact_number
 * @property string|null $contact_email
 * @property string|null $internal_notes
 * @property string|null $external_notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Address $billingAddress
 * @property-read Address $deliveryAddress
 * @property-read Collection|Customer[] $customers
 * @property-read Collection|Order[] $orders
 * @property-read Collection|Quote[] $quotes
 * @property-read Collection|Order[] $memberOrders
 * @property-read Collection|Quote[] $memberQuotes
 * @property-read int|null $customers_count
 * @method static Builder|Organization newModelQuery()
 * @method static Builder|Organization newQuery()
 * @method static Builder|Organization query()
 * @method static Builder|Organization whereContactEmail($value)
 * @method static Builder|Organization whereContactNumber($value)
 * @method static Builder|Organization whereCreatedAt($value)
 * @method static Builder|Organization whereId($value)
 * @method static Builder|Organization whereName($value)
 * @method static Builder|Organization whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Organization extends Model
{
    use HasFactory;
    use HasRelationships;

    protected $guarded = [];

    public function deliveryAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'delivery_address_id');
    }

    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'organization_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'organization_id');
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class, 'organization_id');
    }

    public function memberQuotes(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations(
            $this->customers(),
            (new Customer())->quoteProspects(),
            (new QuoteProspect())->quote(),
        )->groupBy('quotes.id');
    }

    public function memberOrders(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations(
            $this->customers(),
            (new Customer())->orderCustomers(),
            (new OrderCustomer())->order(),
        )->groupBy('orders.id');
    }
}
