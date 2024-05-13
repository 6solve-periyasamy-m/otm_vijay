<?php

namespace App\Models\Traits;

/**
 * @method static find(int $model)
 */
trait Fetches
{
    public static function fetch(self|int|null $model): static
    {
        if (is_int($model)) {
            $model = static::find($model);
        }
        if ($model === null) {
            $model = new static();
        }
        return $model;
    }
}
