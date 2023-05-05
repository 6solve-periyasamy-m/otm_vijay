<?php

namespace App\Models\Customer;

use App\Models\Helper\SimpleModel;
use App\Models\Traits\HasRepository;
use App\Repository\Model\Customer\HatSizeRepository;
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
 * App\Models\HatSize
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Customer[] $customers
 * @property-read HatSizeRepository $repository
 * @method static Builder|HatSize newModelQuery()
 * @method static Builder|HatSize newQuery()
 * @method static QueryBuilder|HatSize onlyTrashed()
 * @method static Builder|HatSize query()
 * @method static Builder|HatSize whereCreatedAt($value)
 * @method static Builder|HatSize whereDeletedAt($value)
 * @method static Builder|HatSize whereId($value)
 * @method static Builder|HatSize whereName($value)
 * @method static Builder|HatSize whereUpdatedAt($value)
 * @method static QueryBuilder|HatSize withTrashed()
 * @method static QueryBuilder|HatSize withoutTrashed()
 * @mixin Eloquent
 */
class HatSize extends SimpleModel
{
    use HasFactory;
    use SoftDeletes;
    use HasRepository;

    protected $fillable = ['name',];

    public static function getValidationRules(int|null $id = null): array
    {
        if (!empty($id)) {
            return [
                'name' => [
                    'required',
                    Rule::unique('hat_sizes', 'name')->ignore($id),
                ],
            ];
        }
        return ['name' => 'required|unique:hat_sizes,name',];
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'hat_size_id');
    }
}
