<?php

namespace App\Models\Customer;

use Database\Factories\Customer\LoyaltyNumberFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Customer\LoyaltyNumber
 *
 * @property int $id
 * @property int $customer_id
 * @property int|null $loyalty_number_type_id
 * @property string $loyalty_number
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Customer $customer
 * @property-read LoyaltyNumberType|null $type
 * @method static LoyaltyNumberFactory factory($count = null, $state = [])
 * @method static Builder|LoyaltyNumber newModelQuery()
 * @method static Builder|LoyaltyNumber newQuery()
 * @method static Builder|LoyaltyNumber query()
 * @method static Builder|LoyaltyNumber whereCreatedAt($value)
 * @method static Builder|LoyaltyNumber whereCustomerId($value)
 * @method static Builder|LoyaltyNumber whereId($value)
 * @method static Builder|LoyaltyNumber whereLoyaltyNumber($value)
 * @method static Builder|LoyaltyNumber whereLoyaltyNumberTypeId($value)
 * @method static Builder|LoyaltyNumber whereNotes($value)
 * @method static Builder|LoyaltyNumber whereUpdatedAt($value)
 * @mixin Eloquent
 */
class LoyaltyNumber extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(LoyaltyNumberType::class, 'loyalty_number_type_id');
    }
}
