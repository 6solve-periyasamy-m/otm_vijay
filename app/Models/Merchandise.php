<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class Merchandise extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name','tour_component_type','stock','purchase_price','tour_sales_price','notes','image_url'];

    public static function getValidationRules()
    {
        return [
            'name',
            'tour_component_type' => [
                'required',
                Rule::in([
                    'Included',
                    'Add-on',
                ])
            ],
            'stock' => 'required|integer',
            'purchase_price' => 'required|numeric',
            'sales_price' => 'required|numeric',];
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function __toString()
    {
        return "{$this->name}";
    }

    public function addToOrder(OrderCustomer $orderCustomer): OrderMerchandise
    {
        return OrderMerchandise::create([
            'order_customer_id' => $orderCustomer->id,
            'merchandise_id' => $this->id,
            'cost' => $this->tour_sales_price
        ]);
    }
}
