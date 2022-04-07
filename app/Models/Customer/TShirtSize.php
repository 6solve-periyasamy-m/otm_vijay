<?php

namespace App\Models\Customer;

use App\Models\Helper\SimpleModel;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\TShirtSize
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
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

    protected $fillable = ['name',];

    public static function getValidationRules(): array
    {
        return ['name' => 'required|unique:t_shirt_sizes,name',];
    }
}
