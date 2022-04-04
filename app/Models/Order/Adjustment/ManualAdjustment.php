<?php

namespace App\Models\Order\Adjustment;

use App\Models\Order\Order;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\ManualAdjustment
 *
 * @property int $id
 * @property int $order_id
 * @property float $amount
 * @property string $reason
 * @property Carbon $date Date the adjustment was made. This is separate from created_at/updated_at, as it may be done retrospectively.
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Order $order
 * @method static Builder|ManualAdjustment newModelQuery()
 * @method static Builder|ManualAdjustment newQuery()
 * @method static QueryBuilder|ManualAdjustment onlyTrashed()
 * @method static Builder|ManualAdjustment query()
 * @method static Builder|ManualAdjustment whereAmount($value)
 * @method static Builder|ManualAdjustment whereCreatedAt($value)
 * @method static Builder|ManualAdjustment whereDate($value)
 * @method static Builder|ManualAdjustment whereDeletedAt($value)
 * @method static Builder|ManualAdjustment whereId($value)
 * @method static Builder|ManualAdjustment whereOrderId($value)
 * @method static Builder|ManualAdjustment whereReason($value)
 * @method static Builder|ManualAdjustment whereUpdatedAt($value)
 * @method static QueryBuilder|ManualAdjustment withTrashed()
 * @method static QueryBuilder|ManualAdjustment withoutTrashed()
 * @mixin Eloquent
 */
class ManualAdjustment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['order_id', 'amount', 'reason', 'date',];
    protected $casts = ['date' => 'date'];

    public static function getValidationRules(): array
    {
        return ['date' => 'required|date', 'amount' => 'required|numeric',];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
