<?php

namespace App\Models\Customer;

use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Models\Quote\Quote;
use App\Models\Quote\QuoteProspect;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

/**
 * App\Models\Customer\Organization
 *
 * @property int $id
 * @property string $name
 * @property string|null $contact_number
 * @property string|null $contact_email
 * @property string|null $internal_notes
 * @property string|null $external_notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Customer[] $customers
 * @property-read Collection|Order[] $orders
 * @property-read Collection|Quote[] $quotes
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
 * @mixin \Eloquent
 */
class Organization extends Model
{
    use HasFactory;
    use HasRelationships;

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'organization_id');
    }

    public function quotes(): HasManyDeep
    {
        return $this->hasManyDeep(Quote::class, [
            Customer::class,
            QuoteProspect::class,
        ])->groupBy('quotes.id');
    }

    public function orders(): HasManyDeep
    {
        return $this->hasManyDeep(Order::class, [
            Customer::class,
            OrderCustomer::class,
        ])->groupBy('orders.id');
    }
}
