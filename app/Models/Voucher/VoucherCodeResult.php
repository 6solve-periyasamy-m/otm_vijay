<?php

namespace App\Models\Voucher;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Voucher\VoucherCodeResult
 *
 * @property int $id
 * @property int $voucher_code_id
 * @property array $data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property ResultType $result_type
 * @property-read VoucherCode|null $voucher
 * @method static Builder|VoucherCodeResult whereCreatedAt($value)
 * @method static Builder|VoucherCodeResult whereData($value)
 * @method static Builder|VoucherCodeResult whereId($value)
 * @method static Builder|VoucherCodeResult whereResultType($value)
 * @method static Builder|VoucherCodeResult whereUpdatedAt($value)
 * @method static Builder|VoucherCodeResult whereVoucherCodeId($value)
 * @method static Builder|VoucherCodeResult newModelQuery()
 * @method static Builder|VoucherCodeResult newQuery()
 * @method static Builder|VoucherCodeResult query()
 * @mixin Eloquent
 */
class VoucherCodeResult extends Model
{
    protected $casts = ['result_type' => ResultType::class, 'data' => 'json'];
    public function voucher(): BelongsTo
    {
        return $this->belongsTo(VoucherCode::class, 'voucher_code_id');
    }
}
