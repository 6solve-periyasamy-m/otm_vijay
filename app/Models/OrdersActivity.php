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
        $activityInventoryTour = $this->activityInventoryTour()->first();
        if ($activityInventoryTour == null) return null;
        $activityInventory = $activityInventoryTour->activityInventory()->first();
        if ($activityInventory == null) return null;
        return $activityInventory->activity();
    }

    public function activityInventory()
    {
        $activityInventoryTour = $this->activityInventoryTour()->first();
        if ($activityInventoryTour == null) return null;
        return $activityInventoryTour->activityInventory();
    }

    public function activityInventoryTour()
    {
        return $this->belongsTo(ActivityInventoryTour::class, 'activity_inventory_tour_id');
    }

    public static function findByOrderCustomer($orderCustomerId)
    {
        $orderActivities = OrdersActivity::where('order_customer_id',$orderCustomerId)->get();


        return $orderActivities;
    }

}
