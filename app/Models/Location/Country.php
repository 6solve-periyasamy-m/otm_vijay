<?php

namespace App\Models\Location;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use function collect;


/**
 * App\Models\Location\Country
 *
 * @property int $id
 * @property string $numeric_code
 * @property string $alpha_code
 * @property string $name
 * @property string|null $dialing_code
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection|Currency[] $currencies
 * @property-read int|null $currencies_count
 * @method static Builder|Country newModelQuery()
 * @method static Builder|Country newQuery()
 * @method static QueryBuilder|Country onlyTrashed()
 * @method static Builder|Country query()
 * @method static Builder|Country whereAlphaCode($value)
 * @method static Builder|Country whereCreatedAt($value)
 * @method static Builder|Country whereDeletedAt($value)
 * @method static Builder|Country whereDialingCode($value)
 * @method static Builder|Country whereId($value)
 * @method static Builder|Country whereName($value)
 * @method static Builder|Country whereNumericCode($value)
 * @method static Builder|Country whereUpdatedAt($value)
 * @method static QueryBuilder|Country withTrashed()
 * @method static QueryBuilder|Country withoutTrashed()
 * @mixin Eloquent
 */
class Country extends Model
{
    use SoftDeletes;

    protected $fillable = ['numeric_code', 'alpha_code', 'name', 'dialing_code'];

    public function currencies(): BelongsToMany
    {
        return $this->belongsToMany(Currency::class, 'country_currencies');
    }

    public function getCurrenciesList(): string
    {
        $codes = [];
        foreach ($this->currencies as $currency) {
            $codes[] = $currency->code;
        }
        return collect($codes)->implode(', ');
    }

    public function __toString()
    {
        return $this->name;
    }
}
