<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class AccommodationInventoryTour extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['tour_id','accommodation_inventory_id','tour_component_type','tour_sales_price',];

    public static function getValidationRules() {
        return [
            'tour_component_type' => [
                'required',
                Rule::in(['Included', 'Upgrade', 'Add-on'])
            ],
            'tour_sales_price' => 'required|numeric',
            'accommodation_inventory_id' => 'required|exists:accommodation_inventories,id'
        ];
    }

    public function accommodationInventory()
    {
        return $this->belongsTo(AccommodationInventory::class, 'accommodation_inventory_id');
    }

    public function orders() {
        return $this->hasMany(OrdersAccommodation::class, 'accommodation_inventory_tour_id');
    }
}
