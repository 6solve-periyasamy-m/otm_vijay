<?php

namespace App\Models\Flight;

use App\Models\Helper\SimpleModel;
use App\Models\Traits\HasRepository;
use App\Repository\Model\Flight\AirlineRepository;
use Database\Factories\Flight\AirlineFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;


/**
 * App\Models\Airline
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection|Flight[] $flights
 * @property-read AirlineRepository $repository
 * @method static AirlineFactory factory(...$parameters)
 * @method static Builder|Airline newModelQuery()
 * @method static Builder|Airline newQuery()
 * @method static QueryBuilder|Airline onlyTrashed()
 * @method static Builder|Airline query()
 * @method static Builder|Airline whereCreatedAt($value)
 * @method static Builder|Airline whereDeletedAt($value)
 * @method static Builder|Airline whereId($value)
 * @method static Builder|Airline whereName($value)
 * @method static Builder|Airline whereUpdatedAt($value)
 * @method static QueryBuilder|Airline withTrashed()
 * @method static QueryBuilder|Airline withoutTrashed()
 * @mixin Eloquent
 */
class Airline extends SimpleModel
{
    use SoftDeletes, HasFactory, HasRepository;

    protected $fillable = ['name',];

    public static function getValidationRules(int|null $id = null): array
    {
        if (!empty($id)) {
            return [
                'name' => [
                    'required',
                    Rule::unique('airlines', 'name')->ignore($id),
                ],
            ];
        }
        return ['name' => 'required|unique:airlines,name',];
    }

    public function flights(): HasMany
    {
        return $this->hasMany(Flight::class, 'airline_id');
    }
}
