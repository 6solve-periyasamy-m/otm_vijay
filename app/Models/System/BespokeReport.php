<?php

namespace App\Models\System;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * \App\Models\System\BespokeReport
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $type
 * @property array $fields
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|BespokeReport newModelQuery()
 * @method static Builder|BespokeReport newQuery()
 * @method static Builder|BespokeReport query()
 * @method static Builder|BespokeReport whereCreatedAt($value)
 * @method static Builder|BespokeReport whereDescription($value)
 * @method static Builder|BespokeReport whereFields($value)
 * @method static Builder|BespokeReport whereId($value)
 * @method static Builder|BespokeReport whereName($value)
 * @method static Builder|BespokeReport whereType($value)
 * @method static Builder|BespokeReport whereUpdatedAt($value)
 * @mixin Eloquent
 */
class BespokeReport extends Model
{
    protected $casts = ['fields' => 'array',];
    protected $guarded = [];

    public function getReport(): \App\Report\BespokeReport
    {
        $type = $this->type;
        return new $type($this->fields);
    }
}
