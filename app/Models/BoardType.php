<?php

namespace App\Models;

use App\Models\Helper\SimpleModel;
use Illuminate\Database\Eloquent\SoftDeletes;


class BoardType extends SimpleModel
{
    use SoftDeletes;

    protected $fillable = ['name',];

    public static function getValidationRules()
    {
        return ['name' => 'required|unique:board_types,name',];
    }

    public static function firstOrCreate(string $name) {
        $type = self::where('name', '=', $name)->first();
        if (!isset($type)) {
            $type = self::create(['name' => $name,]);
        }
        return $type;
    }

    public function __toString()
    {
        return $this->name;
    }
}
