<?php

namespace App\Models\Customer;

use App\Models\Order\OrderCustomer;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Customer\OrderCustomerGroup
 *
 * @property int $id
 * @property int $order_customer_id
 * @property int $group_id
 * @property Carbon|null $deleted_at
 * @property-read Group $group
 * @property-read OrderCustomer $orderCustomer
 * @method static Builder|OrderCustomerGroup newModelQuery()
 * @method static Builder|OrderCustomerGroup newQuery()
 * @method static QueryBuilder|OrderCustomerGroup onlyTrashed()
 * @method static Builder|OrderCustomerGroup query()
 * @method static Builder|OrderCustomerGroup whereDeletedAt($value)
 * @method static Builder|OrderCustomerGroup whereGroupId($value)
 * @method static Builder|OrderCustomerGroup whereId($value)
 * @method static Builder|OrderCustomerGroup whereOrderCustomerId($value)
 * @method static QueryBuilder|OrderCustomerGroup withTrashed()
 * @method static QueryBuilder|OrderCustomerGroup withoutTrashed()
 * @mixin Eloquent
 */
class OrderCustomerGroup extends Pivot
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['group_id', 'order_customer_id'];
    public $timestamps = false;

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function orderCustomer(): BelongsTo
    {
        return $this->belongsTo(OrderCustomer::class, 'order_customer_id');
    }
}
