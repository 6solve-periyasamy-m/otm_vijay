<?php

namespace App\Models;

use App\Repository\ActivityComponentRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderActivity extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['order_customer_id', 'activity_inventory_tour_id'];

    public static function findByOrderCustomer($orderCustomerId)
    {
        $orderActivities = OrderActivity::where('order_customer_id', $orderCustomerId)->get();


        return $orderActivities;
    }

    public function orderCustomers()
    {
        return $this->belongsTo(OrderCustomer::class);
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

}
