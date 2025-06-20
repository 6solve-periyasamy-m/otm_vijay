<?php

namespace App\Models\Customer;

use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Repository\Model\Customer\GroupRepository;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Customer\Group
 *
 * @property int $id
 * @property int|null $room_type_id
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|OrderCustomer[] $orderCustomers
 * @property-read int|null $order_customers_count
 * @property-read Collection|OrderCustomerGroup[] $pivot
 * @property-read int|null $pivot_count
 * @property-read Collection|OrderAccommodation[] $rooms
 * @property-read int|null $rooms_count
 * @property-read Order|null $order
 * @property-read GroupRepository $repository
 * @method static Builder|Group newModelQuery()
 * @method static Builder|Group newQuery()
 * @method static QueryBuilder|Group onlyTrashed()
 * @method static Builder|Group query()
 * @method static Builder|Group whereCreatedAt($value)
 * @method static Builder|Group whereDeletedAt($value)
 * @method static Builder|Group whereId($value)
 * @method static Builder|Group whereName($value)
 * @method static Builder|Group whereRoomTypeId($value)
 * @method static Builder|Group whereUpdatedAt($value)
 * @method static QueryBuilder|Group withTrashed()
 * @method static QueryBuilder|Group withoutTrashed()
 * @mixin Eloquent
 */
class Group extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected array $cascadeDeletes = ['pivot', 'rooms'];
    protected $guarded = [];
    protected $withCount = ['orderCustomers'];
    private GroupRepository $internal_repository;

    public function orderCustomers(): BelongsToMany
    {
        return $this->belongsToMany(OrderCustomer::class, OrderCustomerGroup::class)->using(OrderCustomerGroup::class);
    }

    public function pivot(): HasMany
    {
        return $this->hasMany(OrderCustomerGroup::class, 'group_id');
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(OrderAccommodation::class, 'group_id');
    }

    public function getMembers(OrderCustomer $exclude = null): string
    {
        $members = "";
        foreach ($this->orderCustomers as $orderCustomer) {
            if ($orderCustomer->id == $exclude?->id) continue;
            $members .= $orderCustomer->customer_name . ', ';
        }
        if (empty($members)) return $members;
        return substr($members, 0, -2);
    }

    public function getRepositoryAttribute(): GroupRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new GroupRepository($this);
        return $this->internal_repository;
    }

    public function getOrderAttribute()
    {
        foreach ($this->orderCustomers as $orderCustomer) {
            if ($orderCustomer->order !== null) return $orderCustomer->order;
        }
        return null;
    }
}
