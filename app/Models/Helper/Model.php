<?php

namespace App\Models\Helper;

class Model extends \Illuminate\Database\Eloquent\Model
{
    protected $fillable = [
        'archived',
    ];

    public static function getForMount(Model|int|null $model): static
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