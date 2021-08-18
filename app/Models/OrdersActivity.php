<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdersActivity extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function orderCustomers()
    {
        return $this->belongsTo(OrdersCustomer::class);
    }

    public function activity()
    {
        return $this->hasOne(Activity::class,'id','activity_id');
    }

    public function activityInventory()
    {
        return $this->hasOneThrough(ActivityInventory::class, Activity::class, 'id', 'activity_id', 'activity_id');
    }

    public static function findByOrderCustomer($orderCustomerId)
    {
        $orderActivities = OrdersActivity::where('order_customer_id',$orderCustomerId)->get();


        return $orderActivities;
    }

}
