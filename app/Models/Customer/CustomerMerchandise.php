<?php

namespace App\Models\Customer;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Customer\CustomerMerchandise
 *
 * @property int $id
 * @property int $customer_id
 * @property int|null $merchandise_category_id
 * @property string $size
 * @property string|null $other_details
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Customer $customer
 * @property-read MerchandiseCategory|null $category
 * @method static Builder|CustomerMerchandise newModelQuery()
 * @method static Builder|CustomerMerchandise newQuery()
 * @method static Builder|CustomerMerchandise query()
 * @method static Builder|CustomerMerchandise whereCreatedAt($value)
 * @method static Builder|CustomerMerchandise whereCustomerId($value)
 * @method static Builder|CustomerMerchandise whereId($value)
 * @method static Builder|CustomerMerchandise whereSize($value)
 * @method static Builder|CustomerMerchandise whereMerchandiseCategoryId($value)
 * @method static Builder|CustomerMerchandise whereOtherDetails($value)
 * @method static Builder|CustomerMerchandise whereUpdatedAt($value)
 * @mixin Eloquent
 */
class CustomerMerchandise extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(MerchandiseCategory::class, 'merchandise_category_id');
    }
}
