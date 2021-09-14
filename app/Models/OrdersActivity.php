<?php

namespace App\Models;

use App\Repository\ActivityComponentRepository;
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
        return ActivityComponentRepository::getComponentFromOrderComponent($this->id);
    }

    public function activityInventory()
    {
        return ActivityComponentRepository::getInventoryFromOrderComponent($this->id);
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
