<?php

namespace App\Models\Customer;

use App\Models\Helper\SimpleModel;
use App\Models\Traits\HasRepository;
use App\Repository\Model\Customer\TShirtSizeRepository;
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
 * App\Models\TShirtSize
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Customer[] $customers
 * @property-read TShirtSizeRepository $repository
 * @method static Builder|TShirtSize newModelQuery()
 * @method static Builder|TShirtSize newQuery()
 * @method static QueryBuilder|TShirtSize onlyTrashed()
 * @method static Builder|TShirtSize query()
 * @method static Builder|TShirtSize whereCreatedAt($value)
 * @method static Builder|TShirtSize whereDeletedAt($value)
 * @method static Builder|TShirtSize whereId($value)
 * @method static Builder|TShirtSize whereName($value)
 * @method static Builder|TShirtSize whereUpdatedAt($value)
 * @method static QueryBuilder|TShirtSize withTrashed()
 * @method static QueryBuilder|TShirtSize withoutTrashed()
 * @mixin Eloquent
 */
class TShirtSize extends SimpleModel
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
                    Rule::unique('t_shirt_sizes', 'name')->ignore($id),
                ],
            ];
        }
        return ['name' => 'required|unique:t_shirt_sizes,name',];
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 't_shirt_size_id');
    }
}
