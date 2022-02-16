<?php

namespace App\Models;

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
    protected $fillable = ['room_type_id',];

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
}
