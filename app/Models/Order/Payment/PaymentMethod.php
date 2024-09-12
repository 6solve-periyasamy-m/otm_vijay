<?php

namespace App\Models\Order\Payment;

use App\Models\Helper\SimpleModel;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * App\Models\Order\Payment\PaymentMethod
 *
 * @property int $id
 * @property string $name
 * @property float|null $fee_percentage
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<Payment> $payments
 * @method static Builder|PaymentMethod newModelQuery()
 * @method static Builder|PaymentMethod newQuery()
 * @method static QueryBuilder|PaymentMethod onlyTrashed()
 * @method static Builder|PaymentMethod query()
 * @method static Builder|PaymentMethod whereCreatedAt($value)
 * @method static Builder|PaymentMethod whereDeletedAt($value)
 * @method static Builder|PaymentMethod whereId($value)
 * @method static Builder|PaymentMethod whereName($value)
 * @method static Builder|PaymentMethod whereUpdatedAt($value)
 * @method static QueryBuilder|PaymentMethod withTrashed()
 * @method static QueryBuilder|PaymentMethod withoutTrashed()
 * @mixin Eloquent
 */
class PaymentMethod extends SimpleModel
{
    protected $guarded = [];
    protected $casts = ['fee_percentage' => 'float',];

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'payment_method_id');
    }

    public static function findOrCreate(string $name)
    {
        $type = self::where('name', '=', $name)->first();
        if (!isset($type)) {
            $type = self::create(['name' => $name,]);
        }
        return $type;
    }
}
