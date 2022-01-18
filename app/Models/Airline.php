<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Airline extends Model
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
