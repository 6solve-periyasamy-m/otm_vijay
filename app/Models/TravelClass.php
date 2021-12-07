<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class TravelClass extends Model
{
    use SoftDeletes;

    protected $fillable = ['name',];

    public static function getValidationRules()
    {
        return ['name' => 'required|unique:travel_classes,name',];
    }
}
