<?php

namespace Tests\Traits\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

trait ComparesWithModel
{
    /**
     * @param Model $model
     * @param Array<string, mixed> $data
     * @return false
     */
    public function compareModel(Model $model, array $data): bool
    {
        foreach ($data as $key => $value) {
            if (!isset($model->{$key})) return false;
            if ($model->{$key} instanceof Carbon) {
                if (!($model->{$key}->eq($value->micro(0)))) return false;
            } else {
                if ($model->{$key} != $value) return false;
            }
        }
        return true;
    }
}
