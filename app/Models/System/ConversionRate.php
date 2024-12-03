<?php

namespace App\Models\System;

use App\Models\Location\Currency;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\System\ConversionRate
 *
 * @property int $id
 * @property int $from_currency_id
 * @property int $to_currency_id
 * @property float $rate
 * @property boolean $automatic
 * @property Carbon $changed
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Currency $from
 * @property-read Currency $to
 * @method static Builder|ConversionRate newModelQuery()
 * @method static Builder|ConversionRate newQuery()
 * @method static Builder|ConversionRate query()
 * @method static Builder|ConversionRate whereAutomatic($value)
 * @method static Builder|ConversionRate whereChanged($value)
 * @method static Builder|ConversionRate whereCreatedAt($value)
 * @method static Builder|ConversionRate whereFromCurrencyId($value)
 * @method static Builder|ConversionRate whereId($value)
 * @method static Builder|ConversionRate whereRate($value)
 * @method static Builder|ConversionRate whereToCurrencyId($value)
 * @method static Builder|ConversionRate whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ConversionRate extends Model
{
    protected $guarded = [];
    protected $casts = ['changed' => 'datetime', 'rate' => 'float', 'automatic' => 'boolean'];
    protected $with = ['from', 'to'];

    public function from(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'from_currency_id');
    }

    public function to(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'to_currency_id');
    }
}
