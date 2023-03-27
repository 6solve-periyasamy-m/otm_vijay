<?php

namespace App\Models\Transport;

use App\Models\Helper\SimpleModel;
use App\Models\Traits\HasRepository;
use App\Repository\Model\Transport\OperatorRepository;
use Database\Factories\Transport\OperatorFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;


/**
 * App\Models\Operator
 *
 * @property int $id
 * @property string $name
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection|Transport[] $transports
 * @property-read OperatorRepository $repository
 * @property-read int|null $transports_count
 * @method static OperatorFactory factory(...$parameters)
 * @method static Builder|Operator newModelQuery()
 * @method static Builder|Operator newQuery()
 * @method static QueryBuilder|Operator onlyTrashed()
 * @method static Builder|Operator query()
 * @method static Builder|Operator whereCreatedAt($value)
 * @method static Builder|Operator whereDeletedAt($value)
 * @method static Builder|Operator whereId($value)
 * @method static Builder|Operator whereName($value)
 * @method static Builder|Operator whereNotes($value)
 * @method static Builder|Operator whereUpdatedAt($value)
 * @method static QueryBuilder|Operator withTrashed()
 * @method static QueryBuilder|Operator withoutTrashed()
 * @mixin Eloquent
 */
class Operator extends SimpleModel
{
    use SoftDeletes, HasFactory, HasRepository;

    protected $fillable = ['name', 'notes',];

    public static function getValidationRules(int|null $id = null): array
    {
        if (!empty($id)) {
            return [
                'name' => [
                    'required',
                    Rule::unique('operators', 'name')->ignore($id),
                ],
            ];
        }
        return ['name' => 'required|unique:operators,name',];
    }

    public function transports(): HasMany
    {
        return $this->hasMany(Transport::class, 'operator_id');
    }
}
