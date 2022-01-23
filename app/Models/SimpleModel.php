<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimpleModel extends Model
{
    public static function firstOrCreate(string $name)
    {
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
