<?php

namespace App\Models\Helper\Traits;

trait MountsLivewire
{
    public static function getForMount(self|int|null $model): static
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