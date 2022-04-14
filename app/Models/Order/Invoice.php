<?php

namespace App\Models\Order;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;


/**
 * App\Models\Invoice
 *
 * @property int $id
 * @property int $order_id
 * @property string $number Iteration of the invoice
 * @property Carbon $generated When the invoice was generated
 * @property array $customers
 * @property array $groups
 * @property array $adjustments
 * @property array $payments
 * @property array $installments
 * @property string|null $footer
 * @property float $total_cost
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property string|null $notes
 * @property-read Order $order
 * @method static Builder|Invoice newModelQuery()
 * @method static Builder|Invoice newQuery()
 * @method static QueryBuilder|Invoice onlyTrashed()
 * @method static Builder|Invoice query()
 * @method static Builder|Invoice whereAdjustments($value)
 * @method static Builder|Invoice whereCreatedAt($value)
 * @method static Builder|Invoice whereCustomers($value)
 * @method static Builder|Invoice whereDeletedAt($value)
 * @method static Builder|Invoice whereFooter($value)
 * @method static Builder|Invoice whereGenerated($value)
 * @method static Builder|Invoice whereGroups($value)
 * @method static Builder|Invoice whereId($value)
 * @method static Builder|Invoice whereInstallments($value)
 * @method static Builder|Invoice whereNotes($value)
 * @method static Builder|Invoice whereNumber($value)
 * @method static Builder|Invoice whereOrderId($value)
 * @method static Builder|Invoice wherePayments($value)
 * @method static Builder|Invoice whereTotalCost($value)
 * @method static Builder|Invoice whereUpdatedAt($value)
 * @method static QueryBuilder|Invoice withTrashed()
 * @method static QueryBuilder|Invoice withoutTrashed()
 * @mixin Eloquent
 */
class Invoice extends Model
{
    use SoftDeletes;

    protected $casts = ['customers' => 'array', 'adjustments' => 'array', 'payments' => 'array', 'installments' => 'array', 'groups' => 'array', 'generated' => 'datetime', 'total_cost' => 'double'];
    protected $fillable = ['order_id', 'number', 'generated', 'customers', 'adjustments', 'payments', 'footer', 'total_cost', 'installments', 'groups',];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
