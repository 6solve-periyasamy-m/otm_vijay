<?php

namespace App\Models;

use App\Models\Accommodation\RoomType;
use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\OrderCustomer;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected array $cascadeDeletes = ['pivot', 'rooms'];
    protected $fillable = ['room_type_id','name'];

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

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }

    public function getMembers(OrderCustomer $exclude = null): string
    {
        $members = "";
        foreach ($this->orderCustomers as $orderCustomer) {
            if ($orderCustomer->id == $exclude->id) continue;
            $members .= $orderCustomer->customer_name . ', ';
        }
        if (empty($members)) return $members;
        return substr($members, 0, -2);
    }
}
