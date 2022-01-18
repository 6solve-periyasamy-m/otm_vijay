<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;


class TravelClass extends SimpleModel
{
    use SoftDeletes;

    protected $fillable = ['name',];

    public static function getValidationRules()
    {
        return ['name' => 'required|unique:travel_classes,name',];
    }

    public function __toString()
    {
        return $this->name;
    }
}
