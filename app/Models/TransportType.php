<?php

namespace App\Models;

use App\Models\Helper\SimpleModel;
use Illuminate\Database\Eloquent\SoftDeletes;


class TransportType extends SimpleModel
{
    use SoftDeletes;

    protected $fillable = ['name',];

    public static function getValidationRules(): array
    {
        return ['name' => 'required|unique:transport_types,name',];
    }

    public function __toString()
    {
        return $this->name;
    }
}
