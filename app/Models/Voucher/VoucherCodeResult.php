<?php

namespace App\Models\Voucher;

use App\Models\Voucher\Executors\FlatCostReductionExecutor;
use App\Models\Voucher\Executors\PercentageCostReductionExecutor;
use App\Models\Voucher\Executors\VoucherExecutor;
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
    protected $guarded = [];
    protected $casts = ['result_type' => ResultType::class, 'data' => 'json'];
    public function voucher(): BelongsTo
    {
        return $this->belongsTo(VoucherCode::class, 'voucher_code_id');
    }

    public function executor(): VoucherExecutor|null
    {
        return match ($this->result_type) {
            ResultType::FLAT_ADJUSTMENT => new FlatCostReductionExecutor($this),
            ResultType::PERCENTAGE_ADJUSTMENT => new PercentageCostReductionExecutor($this),
            default => null,
        };
    }
}
