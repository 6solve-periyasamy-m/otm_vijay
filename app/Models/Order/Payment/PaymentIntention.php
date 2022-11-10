<?php

namespace App\Models\Order\Payment;

use App\Events\Order\OrderEditedEvent;
use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\Customer\Customer;
use App\Models\Customer\Group;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Merchandise\MerchandiseInventoryTour;
use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\Component\OrderActivity;
use App\Models\Order\Component\OrderFlight;
use App\Models\Order\Component\OrderMerchandise;
use App\Models\Order\Component\OrderTransport;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Models\Transport\TransportInventoryTour;
use App\Repository\Model\Order\OrderRepository;
use Carbon\Carbon;
use DB;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Log;
use Throwable;
use function app;

/*
  Data field should be in the structure as follows:
  [
   'additions' => [
      [
        'customer' => 'Customer ID (Group ID for accommodation)',
        'component' => 'accommodation/activity/flight/transport/extra',
        'id' => 'Relevant tour component id'
      ]
   ],
   'upgrades' => [
      [
        'customer' => 'Customer ID (Group ID for accommodation)',
        'component' => 'accommodation/activity/flight/transport/extra',
        'from' => 'Relevant from tour component id',
        'to' => 'Relevant new tour component id',
      ],
   ],
   'removals' => [
       [
        'customer' => 'Customer ID (Group ID for accommodation)',
        'component' => 'accommodation/activity/flight/transport/extra',
        'from' => 'Relevant from tour component id',
        'to' => 'Relevant new tour component id',
      ]
   ],
  ]
 */

/**
 * App\Models\Order\Payment\PaymentIntention
 *
 * @property string $id
 * @property int $customer_id Customer who made the payment intention
 * @property string $type Payment type
 * @property string $reference Order reference or Booking token
 * @property array|null $data Data to be processed once the intention is confirmed
 * @property bool $processed Has the intention been processed
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
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $fillable = ['id', 'customer_id', 'reference', 'data', 'type'];
    protected $casts = ['data' => 'array',];

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
            'payment_type' => $this->type,
        ]);
    }

    public function process(): bool
    {
        if (!isset($this->data)) return true;

        $order = OrderRepository::getFromBookingReference($this->reference);
        if (!isset($order)) return false;

        try {
            DB::beginTransaction();
        } catch (Throwable $e) {
            Log::error($e);
            return false;
        }

        if (array_key_exists('additions', $this->data)) {
            foreach ($this->data['additions'] as $key => $datum) {
                $owner = $this->getOwner($datum['component'], $order, $datum['customer']);
                if (!isset($owner)) return $this->handleError('Owner not found on addition');
                $component = $this->findTourComponent($datum['component'], $datum['id']);
                if (!isset($component)) return $this->handleError('Component not found on addition');
                echo $component->addToOrder($owner);
            }
        }

        if (array_key_exists('upgrades', $this->data)) {
            foreach ($this->data['upgrades'] as $key => $datum) {
                $owner = $this->getOwner($datum['component'], $order, $datum['customer']);
                if (!isset($owner)) return $this->handleError('Owner not found on upgrade');
                $orderComponent = $this->findOrderComponent($owner, $datum['component'], $datum['from']);
                $component = $this->findTourComponent($datum['component'], $datum['to']);
                if (!isset($component)) return $this->handleError('New Component not found on upgrade');
                if (!isset($orderComponent)) return $this->handleError('Old component not found on upgrade');
                $orderComponent->swap($component);
            }
        }

        if (array_key_exists('removals', $this->data)) {
            foreach ($this->data['removals'] as $key => $datum) {
                $owner = $this->getOwner($datum['component'], $order, $datum['customer']);
                if (!isset($owner)) return $this->handleError('Owner not found on removal');
                $orderComponent = $this->findOrderComponent($owner, $datum['component'], $datum['id']);
                if (!isset($orderComponent)) return $this->handleError('Component not found on removal');
                $orderComponent->delete();
            }
        }
        try {
            DB::commit();
            event(new OrderEditedEvent($order, true));
        } catch (Throwable $e) {
            Log::error($e);
            return false;
        }

        return true;
    }

    private function getOwner(string $componentType, Order $order, int $id): Group|OrderCustomer|null
    {
        return $componentType == 'accommodation' ?
            Group::find($id) :
            OrderCustomer::where('order_id', '=', $order->id)->where('customer_id', '=', $id)->first();
    }

    private function handleError(string $info = ''): bool
    {
        Log::error('Malformed Payment Intention: ' . $this->id . '. ' . $info);
        try {
            DB::rollBack();
        } catch (Throwable $e) {
            Log::error($e);
            return false;
        }
        return false;
    }

    private function findTourComponent(string $componentType, int $id)
    {
        switch ($componentType) {
            case 'accommodation':
                $model = AccommodationInventoryTour::class;
                break;
            case 'activity':
                $model = ActivityInventoryTour::class;
                break;
            case 'flight':
                $model = FlightInventoryTour::class;
                break;
            case 'transport':
                $model = TransportInventoryTour::class;
                break;
            case 'merchandise':
                $model = MerchandiseInventoryTour::class;
                break;
            default:
                return null;
        }
        return app($model)->find($id);
    }

    private function findOrderComponent(Model $owner, string $componentType, int $id)
    {
        switch ($componentType) {
            case 'accommodation':
                $model = OrderAccommodation::class;
                $customer = 'group_id';
                $field = 'accommodation_inventory_tour_id';
                break;
            case 'activity':
                $model = OrderActivity::class;
                $customer = 'order_customer_id';
                $field = 'activity_inventory_tour_id';
                break;
            case 'flight':
                $model = OrderFlight::class;
                $customer = 'order_customer_id';
                $field = 'flight_inventory_tour_id';
                break;
            case 'transport':
                $model = OrderTransport::class;
                $customer = 'order_customer_id';
                $field = 'transport_inventory_tour_id';
                break;
            case 'merchandise':
                $model = OrderMerchandise::class;
                $customer = 'order_customer_id';
                $field = 'merchandise_id';
                break;
            default:
                return null;
        }
        return app($model)->where($customer, '=', $owner->id)->where($field, '=', $id)->first();
    }
}
