<?php

namespace App\Models\Order\Payment;

use App\Models\Booking\Booking;
use App\Models\Customer\Customer;
use App\Models\Order\Order;
use App\Models\System\Brand;
use App\Repository\Intention\PaymentIntentionRepository;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Order\Payment\PaymentIntention
 *
 * @property string $id
 * @property int $customer_id Customer who made the payment intention
 * @property string $type Payment type
 * @property string $reference Order reference or Booking token
 * @property array|null $data Data to be processed once the intention is confirmed
 * @property float|null $amount
 * @property bool $processed Has the intention been processed
 * @property-read PaymentIntentionRepository $repository
 * @method static Builder|PaymentIntention newModelQuery()
 * @method static Builder|PaymentIntention newQuery()
 * @method static Builder|PaymentIntention query()
 * @method static Builder|PaymentIntention whereCustomerId($value)
 * @method static Builder|PaymentIntention whereData($value)
 * @method static Builder|PaymentIntention whereId($value)
 * @method static Builder|PaymentIntention whereProcessed($value)
 * @method static Builder|PaymentIntention whereReference($value)
 * @method static Builder|PaymentIntention whereType($value)
 * @mixin Eloquent
 */
class PaymentIntention extends Model
{
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $fillable = ['id', 'customer_id', 'reference', 'data', 'type'];
    protected $casts = ['data' => 'array', 'amount' => 'float'];

    private PaymentIntentionRepository $repo;

    public static function build(?Customer $customer, string $reference, string $type, ?array $data = null): PaymentIntention
    {
        do {
            $key = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(32 / strlen($x)))), 1, 32);
        } while (self::fetch($key) != null);
        return PaymentIntention::create([
            'id' => $key,
            'reference' => $reference,
            'customer_id' => $customer?->id,
            'data' => $data,
            'type' => $type,
        ]);
    }

    public static function fetch(string $id): ?PaymentIntention
    {
        return PaymentIntention::where('id', '=', $id)->first();
    }

    public function makePayment(float $amount, PaymentMethod $method, $created): Payment
    {
        return Payment::make([
            'payment_method_id' => $method->id,
            'paid_on' => Carbon::parse($created),
            'customer_id' => $this->customer_id,
            'amount' => $amount,
        ]);
    }

    public function process(): bool
    {
        if (!isset($this->data)) return true;

        return $this->repository->process();
    }

    public function getRepositoryAttribute(): PaymentIntentionRepository
    {
        $this->repo = $this->repo ?? new PaymentIntentionRepository($this);
        return $this->repo;
    }

    public function getBrand(): Brand
    {
        return $this->getRelatedModel()?->tour?->brand ?? Brand::getSystemBrand();
    }

    public function getRelatedModel(): Order|Booking|null
    {
        return Order::where('booking_reference', '=', $this->reference)->first()
            ?? Booking::where('token', '=', $this->reference)->first();
    }
}
