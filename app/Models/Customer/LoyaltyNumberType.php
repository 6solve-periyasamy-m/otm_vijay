<?php

namespace App\Models\Customer;

use App\Models\Helper\SimpleModel;
use App\Models\Traits\HasRepository;
use App\Repository\Model\Customer\LoyaltyNumberTypeRepository;
use Database\Factories\Customer\LoyaltyNumberTypeFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Customer\LoyaltyNumberType
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, LoyaltyNumber> $numbers
 * @property-read int|null $numbers_count
 * @property-read LoyaltyNumberTypeRepository $repository
 * @method static LoyaltyNumberTypeFactory factory($count = null, $state = [])
 * @method static Builder|LoyaltyNumberType newModelQuery()
 * @method static Builder|LoyaltyNumberType newQuery()
 * @method static Builder|LoyaltyNumberType query()
 * @method static Builder|LoyaltyNumberType whereCreatedAt($value)
 * @method static Builder|LoyaltyNumberType whereId($value)
 * @method static Builder|LoyaltyNumberType whereName($value)
 * @method static Builder|LoyaltyNumberType whereUpdatedAt($value)
 * @mixin Eloquent
 */
class LoyaltyNumberType extends SimpleModel
{
    use HasFactory, HasRepository;

    public function numbers(): HasMany
    {
        return $this->hasMany(LoyaltyNumber::class, 'loyalty_number_type_id');
    }
}
