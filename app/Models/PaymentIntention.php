<?php

namespace App\Models;

use App\Repository\OrderRepository;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;
use Log;

/**
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
class PaymentIntention extends Model
{
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $fillable = ['id', 'customer_id', 'reference', 'data', 'type'];

    public static function build(Customer $customer, string $reference, string $type, ?array $data = null): PaymentIntention
    {
        do {
            $key = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(32/strlen($x)) )),1,32);
        } while(self::fetch($key) != null);
        return PaymentIntention::create([
            'id' => $key,
            'reference' => $reference,
            'customer_id' => $customer->id,
            'data' => $data,
            'type' => $type,
        ]);
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

    public static function fetch(string $id): ?PaymentIntention
    {
        return PaymentIntention::where('id', '=', $id)->first();
    }

    public function process(): bool
    {
        if (!isset($this->data)) return true;

        $order = OrderRepository::getOrderFromBookingReference($this->reference);
        if (!isset($order)) return false;

        try { DB::beginTransaction(); } catch (\Throwable $e) { Log::error($e); return false; }

        if (array_key_exists('additions', $this->data)) {
            foreach ($this->data['additions'] as $datum) {
                $owner = $this->getOwner($datum['component'], $order, $datum['customer']);
                if (!isset($owner)) return $this->handleError('Owner not found on addition');
                $component = $this->findTourComponent($datum['component'], $datum['id']);
                if (!isset($component)) return $this->handleError('Component not found on addition');
                echo $component->addToOrder($owner);
            }
        }

        if (array_key_exists('upgrades', $this->data)) {
            foreach ($this->data['upgrades'] as $datum) {
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
            foreach ($this->data['removals'] as $datum) {
                $owner = $this->getOwner($datum['component'], $order, $datum['customer']);
                if (!isset($owner)) return $this->handleError('Owner not found on removal');
                $orderComponent = $this->findOrderComponent($owner, $datum['component'], $datum['id']);
                if (!isset($orderComponent)) return $this->handleError('Component not found on removal');
                $orderComponent->delete();
            }
        }
        try { DB::commit(); } catch (\Throwable $e) { Log::error($e); return false; }

        return true;
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
            case 'extra':
                $model = OrderMerchandise::class;
                $customer = 'order_customer_id';
                $field = 'merchandise_id';
                break;
            default:
                return null;
        }
        return app($model)->where($customer, '=', $owner->id)->where($field, '=', $id)->first();
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
            case 'extra':
                $model = Merchandise::class;
                break;
            default:
                return null;
        }
        return app($model)->find($id);
    }

    private function getOwner(string $componentType, Order $order, int $id)
    {
        return $componentType == 'accommodation' ?
            Group::find($id) :
            OrderCustomer::where('order_id', '=', $order->id)->where('customer_id', '=', $id)->first();
    }

    private function handleError(string $info = ''): bool
    {
        Log::error('Malformed Payment Intention: ' . $this->id . '. ' . $info);
        try { DB::rollBack(); } catch (\Throwable $e) { Log::error($e); return false; }
        return false;
    }
}
