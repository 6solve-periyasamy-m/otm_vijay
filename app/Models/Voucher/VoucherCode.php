<?php

namespace App\Models\Voucher;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Voucher\VoucherCode
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $code
 * @property int $active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|VoucherCodeResult[] $results
 * @property-read int|null $results_count
 * @method static Builder|VoucherCode whereActive($value)
 * @method static Builder|VoucherCode whereCode($value)
 * @method static Builder|VoucherCode whereCreatedAt($value)
 * @method static Builder|VoucherCode whereDescription($value)
 * @method static Builder|VoucherCode whereId($value)
 * @method static Builder|VoucherCode whereName($value)
 * @method static Builder|VoucherCode whereUpdatedAt($value)
 * @method static Builder|VoucherCode newModelQuery()
 * @method static Builder|VoucherCode newQuery()
 * @method static Builder|VoucherCode query()
 * @mixin Eloquent
 */
class VoucherCode extends Model
{
    protected $casts = ['active' => 'boolean',];
    protected $with = ['results',];
    public function results(): HasMany
    {
        return $this->hasMany(VoucherCodeResult::class, 'voucher_code_id');
    }
}
