<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\CustomerOrderDetail
 *
 * @property int $id
 * @property int $order_customer_id
 * @property string $type
 * @property int $inventory_tour_id
 * @property string $date_time
 * @property string $status
 * @property string $reference
 * @property int $addon
 * @property string $cost
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @method static Builder|CustomerOrderDetail newModelQuery()
 * @method static Builder|CustomerOrderDetail newQuery()
 * @method static QueryBuilder|CustomerOrderDetail onlyTrashed()
 * @method static Builder|CustomerOrderDetail query()
 * @method static Builder|CustomerOrderDetail whereAddon($value)
 * @method static Builder|CustomerOrderDetail whereCost($value)
 * @method static Builder|CustomerOrderDetail whereCreatedAt($value)
 * @method static Builder|CustomerOrderDetail whereDateTime($value)
 * @method static Builder|CustomerOrderDetail whereDeletedAt($value)
 * @method static Builder|CustomerOrderDetail whereId($value)
 * @method static Builder|CustomerOrderDetail whereInventoryTourId($value)
 * @method static Builder|CustomerOrderDetail whereOrderCustomerId($value)
 * @method static Builder|CustomerOrderDetail whereReference($value)
 * @method static Builder|CustomerOrderDetail whereStatus($value)
 * @method static Builder|CustomerOrderDetail whereType($value)
 * @method static Builder|CustomerOrderDetail whereUpdatedAt($value)
 * @method static QueryBuilder|CustomerOrderDetail withTrashed()
 * @method static QueryBuilder|CustomerOrderDetail withoutTrashed()
 * @mixin Eloquent
 */
class CustomerOrderDetail extends Model
{
    use HasFactory;
    use SoftDeletes;
}
