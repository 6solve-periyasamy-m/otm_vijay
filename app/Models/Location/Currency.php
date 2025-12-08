<?php

namespace App\Models\Location;

use App\Models\Helper\Traits\MountsLivewire;
use Database\Factories\Location\CurrencyFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Location\Currency
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $symbol
 * @property boolean $priority
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection|Country[] $countries
 * @property-read int|null $countries_count
 * @method static CurrencyFactory factory()
 * @method static Builder|Currency newModelQuery()
 * @method static Builder|Currency newQuery()
 * @method static QueryBuilder|Currency onlyTrashed()
 * @method static Builder|Currency query()
 * @method static Builder|Currency whereCode($value)
 * @method static Builder|Currency whereCreatedAt($value)
 * @method static Builder|Currency whereDeletedAt($value)
 * @method static Builder|Currency whereId($value)
 * @method static Builder|Currency whereName($value)
 * @method static Builder|Currency whereSymbol($value)
 * @method static Builder|Currency whereUpdatedAt($value)
 * @method static QueryBuilder|Currency withTrashed()
 * @method static QueryBuilder|Currency withoutTrashed()
 * @mixin Eloquent
 */
class Currency extends Model
{
    use SoftDeletes, HasFactory, MountsLivewire;

    protected $guarded = [];
    protected $casts = ['priority' => 'boolean'];

    public static function fromCode(string|null $code = null): Currency|null
    {
        if ($code === null) return null;
        return Currency::where('code', '=', $code)->first();
    }

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class, 'country_currencies');
    }

    public function __toString()
    {
        return $this->name . ' - ' . $this->code;
    }
}
