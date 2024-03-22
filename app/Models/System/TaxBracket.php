<?php

namespace App\Models\System;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\System\TaxBracket
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property float|null $rate
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|TaxBracket newModelQuery()
 * @method static Builder|TaxBracket newQuery()
 * @method static Builder|TaxBracket query()
 * @method static Builder|TaxBracket whereCreatedAt($value)
 * @method static Builder|TaxBracket whereDescription($value)
 * @method static Builder|TaxBracket whereId($value)
 * @method static Builder|TaxBracket whereName($value)
 * @method static Builder|TaxBracket whereRate($value)
 * @method static Builder|TaxBracket whereUpdatedAt($value)
 * @mixin Eloquent
 */
class TaxBracket extends Model
{
    protected $guarded = [];
    protected $casts = ['rate' => 'float',];

    public function calculate(float $amount): float|null
    {
        if ($this->rate === null) {
            return null;
        } else if ($this->rate === 0) {
            return 0;
        } else {
            return sigfig($amount * sigfig($this->rate / 100));
        }
    }
}
