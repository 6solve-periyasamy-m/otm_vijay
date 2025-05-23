<?php

namespace App\Models\Accommodation;

use App\Models\Helper\SimpleModel;
use App\Models\Traits\HasRepository;
use App\Repository\Model\Accommodation\AccommodationTypeRepository;
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
 * App\Models\AccommodationType
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Accommodation $accommodation
 * @property-read AccommodationTypeRepository $repository
 * @method static Builder|AccommodationType newModelQuery()
 * @method static Builder|AccommodationType newQuery()
 * @method static QueryBuilder|AccommodationType onlyTrashed()
 * @method static Builder|AccommodationType query()
 * @method static Builder|AccommodationType whereCreatedAt($value)
 * @method static Builder|AccommodationType whereDeletedAt($value)
 * @method static Builder|AccommodationType whereId($value)
 * @method static Builder|AccommodationType whereName($value)a
 * @method static Builder|AccommodationType whereUpdatedAt($value)
 * @method static QueryBuilder|AccommodationType withTrashed()
 * @method static QueryBuilder|AccommodationType withoutTrashed()
 * @mixin Eloquent
 */
class AccommodationType extends SimpleModel
{
    use SoftDeletes, HasFactory, HasRepository;

    protected $fillable = ['name'];

    public static function getValidationRules(int|null $id = null): array
    {
        if (!empty($id)) {
            return [
                'name' => [
                    'required',
                    Rule::unique('accommodation_types', 'name')->ignore($id),
                ],
            ];
        }
        return ['name' => 'required|unique:accommodation_types,name',];
    }

    public function accommodations(): HasMany
    {
        return $this->hasMany(Accommodation::class, 'accommodation_type_id');
    }
}
