<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Merchandise extends Model
{
    use HasFactory;

    protected $fillable = ['name','tour_component_type','stock','purchase_price','sales_price','notes'];

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
}
