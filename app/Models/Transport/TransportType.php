<?php

namespace App\Models\Transport;

use App\Models\Helper\SimpleModel;
use App\Models\Traits\HasRepository;
use App\Repository\Model\Transport\TransportTypeRepository;
use Database\Factories\Transport\TransportTypeFactory;
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
 * App\Models\TransportType
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection|Transport[] $transports
 * @property-read TransportTypeRepository $repository
 * @method static TransportTypeFactory factory(...$parameters)
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
 * @mixin Eloquent
 */
class TransportType extends SimpleModel
{
    use SoftDeletes, HasFactory, HasRepository;

    protected $fillable = ['name',];

    public static function getValidationRules(int|null $id = null): array
    {
        if (!empty($id)) {
            return [
                'name' => [
                    'required',
                    Rule::unique('transport_types', 'name')->ignore($id),
                ],
            ];
        }
        return ['name' => 'required|unique:transport_types,name',];
    }

    public function transports(): HasMany
    {
        return $this->hasMany(Transport::class, 'transport_type_id');
    }
}
