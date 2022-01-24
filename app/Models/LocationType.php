<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;


class LocationType extends SimpleModel
{
    use SoftDeletes;

    protected $fillable = ['name',];

    public static function getValidationRules()
    {
        return ['name' => 'required|unique:location_types,name',];
    }
}
