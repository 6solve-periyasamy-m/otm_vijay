<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccommodationInventoryTour extends Model
{
    use HasFactory;
    use SoftDeletes, CascadeSoftDeletes;

    protected $fillable = ['tour_id','accommodation_inventory_id','tour_component_type','tour_sales_price',];
    protected $cascadeDeletes = ['orders'];

    public function accommodationInventory()
    {
        return $this->belongsTo(AccommodationInventory::class, 'accommodation_inventory_id');
    }

    public function orders() {
        return $this->hasMany(OrdersAccommodation::class, 'accommodation_inventory_tour_id');
    }
}
