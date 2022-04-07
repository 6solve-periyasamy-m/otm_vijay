<?php

namespace App\Models;

use App\Models\Accommodation\RoomType;
use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\OrderCustomer;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CascadeSoftDeletes;

    protected $cascadeDeletes = ['pivot', 'rooms'];
    protected $fillable = ['room_type_id','name'];

    public function orderCustomers()
    {
        return $this->belongsToMany(OrderCustomer::class, OrderCustomerGroup::class)->using(OrderCustomerGroup::class);
    }

    public function pivot()
    {
        return $this->hasMany(OrderCustomerGroup::class, 'group_id');
    }

    public function rooms()
    {
        return $this->hasMany(OrderAccommodation::class, 'group_id');
    }

    public function roomType()
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
