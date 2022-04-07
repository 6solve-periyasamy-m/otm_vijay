<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use function collect;


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
