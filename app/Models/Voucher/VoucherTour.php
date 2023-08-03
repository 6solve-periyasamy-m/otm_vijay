<?php

namespace App\Models\Voucher;

use App\Models\Tour\Tour;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Voucher\VoucherTour
 *
 * @property int $id
 * @property int $tour_id
 * @property int $voucher_code_id
 * @property int $invert
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Tour $tour
 * @property-read VoucherCode $voucher
 * @method static Builder|VoucherTour newModelQuery()
 * @method static Builder|VoucherTour newQuery()
 * @method static Builder|VoucherTour query()
 * @method static Builder|VoucherTour whereCreatedAt($value)
 * @method static Builder|VoucherTour whereId($value)
 * @method static Builder|VoucherTour whereInvert($value)
 * @method static Builder|VoucherTour whereTourId($value)
 * @method static Builder|VoucherTour whereUpdatedAt($value)
 * @method static Builder|VoucherTour whereVoucherCodeId($value)
 * @mixin Eloquent
 */
class VoucherTour extends Model
{
    protected $casts = ['invert' => 'boolean',];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(VoucherCode::class, 'voucher_code_id');
    }
}
