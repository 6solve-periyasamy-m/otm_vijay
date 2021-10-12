<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityInventoryTour extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'activity_inventory_tour';

    protected $fillable = ['tour_id','activity_inventory_id','tour_component_type','tour_sales_price',];

    public function activityInventory() {
        return $this->belongsTo(ActivityInventory::class);
    }


    public function orders() {
        return $this->hasMany(OrdersActivity::class, 'activity_inventory_tour_id');
    }
}
