<?php

namespace App\Models;

use App\Models\Helper\SimpleModel;
use Illuminate\Database\Eloquent\SoftDeletes;


class Airline extends SimpleModel
{
    use SoftDeletes;

    protected $fillable = ['name',];

    public static function getValidationRules()
    {
        return ['name' => 'required|unique:airlines,name',];
    }

    public function __toString()
    {
        return $this->name;
    }
}
