<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class RoomType extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'maximum_occupancy',];

    public static function getValidationRules()
    {
        return ['name' => 'required|unique:room_types,name',];
    }
}
