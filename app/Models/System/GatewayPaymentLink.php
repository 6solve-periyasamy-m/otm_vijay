<?php

namespace App\Models\System;

use App\Models\Action;
use App\Models\Order\Payment\PaymentIntention;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\System\GatewayPaymentLink
 *
 * @property int $id
 * @property string $gateway Which gateway is being referenced
 * @property string $payment_reference What is the gateway payment reference
 * @property string $payment_intention_id The payment intention ID
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read PaymentIntention|null $intention
 * @method static Builder|Action newModelQuery()
 * @method static Builder|Action newQuery()
 * @method static Builder|Action query()
 * @method static Builder|Action whereAction($value)
 * @method static Builder|Action whereCreatedAt($value)
 * @method static Builder|Action whereCustomerId($value)
 * @method static Builder|Action whereDetail($value)
 * @method static Builder|Action whereId($value)
 * @method static Builder|Action whereOrderId($value)
 * @method static Builder|Action whereReference($value)
 * @method static Builder|Action whereUpdatedAt($value)
 * @mixin Eloquent
 */
class GatewayPaymentLink extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function intention(): BelongsTo
    {
        return $this->belongsTo(PaymentIntention::class, 'payment_intention_id');
    }

    public static function get(string $gateway, string $reference): ?GatewayPaymentLink
    {
        return GatewayPaymentLink::where('gateway', '=', $gateway)->where('payment_reference', '=', $reference)->first();
    }
}
