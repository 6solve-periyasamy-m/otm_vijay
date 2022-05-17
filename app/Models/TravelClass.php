<?php

namespace App\Models;

use App\Models\Helper\SimpleModel;
use Database\Factories\TravelClassFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;


/**
 * App\Models\TravelClass
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @method static TravelClassFactory factory(...$parameters)
 * @method static Builder|TravelClass newModelQuery()
 * @method static Builder|TravelClass newQuery()
 * @method static QueryBuilder|TravelClass onlyTrashed()
 * @method static Builder|TravelClass query()
 * @method static Builder|TravelClass whereCreatedAt($value)
 * @method static Builder|TravelClass whereDeletedAt($value)
 * @method static Builder|TravelClass whereId($value)
 * @method static Builder|TravelClass whereName($value)
 * @method static Builder|TravelClass whereUpdatedAt($value)
 * @method static QueryBuilder|TravelClass withTrashed()
 * @method static QueryBuilder|TravelClass withoutTrashed()
 * @mixin Eloquent
 */
class TravelClass extends SimpleModel
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['name',];

    public static function getValidationRules(): array
    {
        return ['name' => 'required|unique:travel_classes,name',];
    }
}
