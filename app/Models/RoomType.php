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

    public static function firstOrCreate(string $name, int $maximumOccupancy) {
        $type = self::where('name', '=', $name)->first();
        if (!isset($type)) {
            $type = self::create(['name' => $name,'maximum_occupancy' => $maximumOccupancy,]);
        }
        return $type;
    }
}
