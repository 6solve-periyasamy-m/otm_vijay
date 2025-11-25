<?php

namespace App\Models\Customer;

use App\Models\Helper\SimpleModel;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * \App\Models\Customer\AirlineFrequentFlyers
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Customer $customers
 * @property-read int|null $inventories_count
 * @method static Builder|AirlineFrequentFlyers newModelQuery()
 * @method static Builder|AirlineFrequentFlyers newQuery()
 * @method static Builder|AirlineFrequentFlyers query()
 * @method static Builder|AirlineFrequentFlyers whereCreatedAt($value)
 * @method static Builder|AirlineFrequentFlyers whereId($value)
 * @method static Builder|AirlineFrequentFlyers whereName($value)
 * @method static Builder|AirlineFrequentFlyers whereUpdatedAt($value)
 * @mixin Eloquent
 */
class AirlineFrequentFlyers extends SimpleModel
{
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'airline_frequent_flyers_id');
    }
}
