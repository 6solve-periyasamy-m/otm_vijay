<?php

namespace App\Models\Helper;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Helper\SimpleModel
 *
 * @method static Builder|SimpleModel newModelQuery()
 * @method static Builder|SimpleModel newQuery()
 * @method static Builder|SimpleModel query()
 * @mixin Eloquent
 */
class SimpleModel extends Model
{
    public static function findOrCreate(string $name)
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
