<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;


class TransportType extends SimpleModel
{
    use SoftDeletes;

    protected $fillable = ['name',];

    public static function getValidationRules()
    {
        return ['name' => 'required|unique:transport_types,name',];
    }

    public function __toString()
    {
        return $this->name;
    }
}
