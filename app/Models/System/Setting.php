<?php

namespace App\Models\System;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Setting
 *
 * @property string $key
 * @property string $value
 * @method static Builder|Setting newModelQuery()
 * @method static Builder|Setting newQuery()
 * @method static Builder|Setting query()
 * @method static Builder|Setting whereKey($value)
 * @method static Builder|Setting whereValue($value)
 * @mixin Eloquent
 */
class Setting extends Model
{
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = 'key';
    protected $keyType = 'string';
    protected $fillable = ['key', 'value'];
}
