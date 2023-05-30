<?php

namespace App\Repository\Intention;

use App\Models\Customer\Customer;
use App\Models\Order\Order;
use App\Models\Order\Payment\PaymentIntention;
use App\Repository\Intention\Storage\AdditionIntention;
use App\Repository\Intention\Storage\IntentionAction;
use App\Repository\Intention\Storage\RemovalIntention;
use App\Repository\Intention\Storage\UpgradeIntention;
use DB;
use Exception;
use Illuminate\Support\Facades\Log;

/*
  Data field should be in the structure as follows:
  [
   'additions' => [
      [
        'customer' => 'Order Customer ID',
        'component' => 'accommodation/activity/flight/transport/extra',
        'id' => 'Relevant tour component id'
      ]
   ],
   'upgrades' => [
      [
        'customer' => 'Order Customer ID',
        'component' => 'accommodation/activity/flight/transport/extra',
        'from' => 'Relevant from tour component id',
        'to' => 'Relevant new tour component id',
      ],
   ],
   'removals' => [
       [
        'customer' => 'Order Customer ID',
        'component' => 'accommodation/activity/flight/transport/extra',
        'from' => 'Relevant from tour component id',
        'to' => 'Relevant new tour component id',
      ]
   ],
  ]
 */
class PaymentIntentionRepository
{
    private PaymentIntention $intention;

    public function __construct(PaymentIntention $intention)
    {
        $this->intention = $intention;
    }

    /**
     * @param Order $order
     * @param Customer $customer
     * @param string $type
     * @param IntentionAction[] $actions
     * @return PaymentIntention
     */
    public static function create(Order $order, Customer $customer, string $type, array $actions): PaymentIntention
    {
        do {
            $key = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(32 / strlen($x)))), 1, 32);
        } while (PaymentIntention::fetch($key) != null);
        $data = ['additions' => [], 'upgrades' => [], 'removals' => []];
        foreach ($actions as $action) {
            match (true) {
                $action instanceof AdditionIntention => $data['additions'][] = $action->array(),
                $action instanceof UpgradeIntention => $data['upgrades'][] = $action->array(),
                $action instanceof RemovalIntention => $data['removals'][] = $action->array(),
            };
        }
        return PaymentIntention::create([
            'id' => $key,
            'reference' => $order->booking_reference,
            'customer_id' => $customer->id,
            'data' => json_encode($data),
            'type' => $type,
        ]);
    }

    public function process(): bool
    {
        $data = json_decode($this->intention->data, true);
        try {
            DB::beginTransaction();
            foreach ($data['additions'] as $datum) {
                $intention = new AdditionIntention($datum);
                $intention->process() || throw new Exception('Failed');
            }
            foreach ($data['upgrades'] as $datum) {
                $intention = new UpgradeIntention($datum);
                $intention->process() || throw new Exception('Failed');
            }
            foreach ($data['removals'] as $datum) {
                $intention = new RemovalIntention($datum);
                $intention->process() || throw new Exception('Failed');
            }
            DB::commit();
        } catch (Exception) {
            DB::rollBack();
            Log::error('Failed processing of intention ' . $this->intention->id);
            return false;
        }
        return true;
    }
}
