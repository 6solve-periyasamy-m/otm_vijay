<?php

namespace App\Models\Activity;

use App\Models\Helper\SimpleModel;
use App\Models\Traits\HasRepository;
use App\Repository\Model\Activity\ActivityTypeRepository;
use Database\Factories\Activity\ActivityTypeFactory;
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
 * App\Models\ActivityType
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection|Activity[] $activities
 * @property-read ActivityTypeRepository $repository
 * @method static ActivityTypeFactory factory(...$parameters)
 * @method static Builder|ActivityType newModelQuery()
 * @method static Builder|ActivityType newQuery()
 * @method static QueryBuilder|ActivityType onlyTrashed()
 * @method static Builder|ActivityType query()
 * @method static Builder|ActivityType whereCreatedAt($value)
 * @method static Builder|ActivityType whereDeletedAt($value)
 * @method static Builder|ActivityType whereId($value)
 * @method static Builder|ActivityType whereName($value)
 * @method static Builder|ActivityType whereUpdatedAt($value)
 * @method static QueryBuilder|ActivityType withTrashed()
 * @method static QueryBuilder|ActivityType withoutTrashed()
 * @mixin Eloquent
 */
class ActivityType extends SimpleModel
{
    use SoftDeletes, HasFactory, HasRepository;

    protected $fillable = ['name',];

    public static function getValidationRules(int|null $id = null): array
    {
        if (!empty($id)) {
            return [
                'name' => [
                    'required',
                    Rule::unique('activity_types', 'name')->ignore($id),
                ],
            ];
        }
        return ['name' => 'required|unique:activity_types,name',];
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'activity_type_id');
    }
}
