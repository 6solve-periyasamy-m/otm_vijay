<?php

namespace App\Models;

use App\Models\Helper\SimpleModel;
use Illuminate\Database\Eloquent\SoftDeletes;


class TravelClass extends SimpleModel
{
    use SoftDeletes;

    protected $fillable = ['name',];

    public static function getValidationRules(): array
    {
        return ['name' => 'required|unique:travel_classes,name',];
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
