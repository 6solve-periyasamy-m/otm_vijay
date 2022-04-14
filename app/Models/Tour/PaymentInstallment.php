<?php

namespace App\Models\Tour;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Tour\PaymentInstallment
 *
 * @property int $id
 * @property int $tour_id
 * @property bool $is_percentage
 * @property float $amount
 * @property Carbon|null $due_on
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read float $cost
 * @property-read float $percentage
 * @property-read Tour $tour
 * @method static Builder|PaymentInstallment newModelQuery()
 * @method static Builder|PaymentInstallment newQuery()
 * @method static QueryBuilder|PaymentInstallment onlyTrashed()
 * @method static Builder|PaymentInstallment query()
 * @method static Builder|PaymentInstallment whereAmount($value)
 * @method static Builder|PaymentInstallment whereCreatedAt($value)
 * @method static Builder|PaymentInstallment whereDeletedAt($value)
 * @method static Builder|PaymentInstallment whereDueOn($value)
 * @method static Builder|PaymentInstallment whereId($value)
 * @method static Builder|PaymentInstallment whereIsPercentage($value)
 * @method static Builder|PaymentInstallment whereTourId($value)
 * @method static Builder|PaymentInstallment whereUpdatedAt($value)
 * @method static QueryBuilder|PaymentInstallment withTrashed()
 * @method static QueryBuilder|PaymentInstallment withoutTrashed()
 * @mixin Eloquent
 */
class PaymentInstallment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['due_on', 'amount', 'is_percentage'];
    protected $casts = ['due_on' => 'date', 'is_percentage' => 'boolean', 'amount' => 'double'];

    public static function getValidationRules(): array
    {
        return ['due_on' => 'required|date', 'amount' => 'required|numeric',];
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function getCostAttribute(): float
    {
        if ($this->is_percentage) {
            return round($this->tour->base_price_per_person * ($this->amount / 100), 2);
        } else {
            return $this->amount;
        }
    }

    public function getPercentageAttribute(): float
    {
        if ($this->is_percentage) {
            return $this->amount;
        } else {
            return round(($this->amount / $this->tour->base_price_per_person) * 100, 2);
        }
    }
}
