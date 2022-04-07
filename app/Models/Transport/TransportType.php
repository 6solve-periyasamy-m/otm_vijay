<?php

namespace App\Models\Transport;

use App\Models\Helper\SimpleModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;


/**
 * App\Models\TransportType
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @method static Builder|TransportType newModelQuery()
 * @method static Builder|TransportType newQuery()
 * @method static QueryBuilder|TransportType onlyTrashed()
 * @method static Builder|TransportType query()
 * @method static Builder|TransportType whereCreatedAt($value)
 * @method static Builder|TransportType whereDeletedAt($value)
 * @method static Builder|TransportType whereId($value)
 * @method static Builder|TransportType whereName($value)
 * @method static Builder|TransportType whereUpdatedAt($value)
 * @method static QueryBuilder|TransportType withTrashed()
 * @method static QueryBuilder|TransportType withoutTrashed()
 * @mixin \Eloquent
 */
class TransportType extends SimpleModel
{
    use SoftDeletes;

    protected $fillable = ['name',];

    public static function getValidationRules(): array
    {
        return ['name' => 'required|unique:transport_types,name',];
    }
}
