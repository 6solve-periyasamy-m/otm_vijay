<?php

namespace App\Repository\Intention\Storage;

use App\Models\Order\OrderCustomer;
use App\Repository\Abstracts\InventoryTourRepository;

/*
  [
    'customer' => 'Order Customer ID',
    'component' => 'accommodation/activity/flight/transport/extra',
    'id' => 'Relevant tour component id'
  ]
 */
class UpgradeIntention extends IntentionAction
{
    public static function create(OrderCustomer $customer, InventoryTourRepository $from, InventoryTourRepository $to): IntentionAction
    {
        return new self([
            'customer' => $customer->id,
            'from_component' => $from->getComponentType(),
            'from_id' => $from->get()->id,
            'to_component' => $to->getComponentType(),
            'to_id' => $to->get()->id
        ]);
    }

    public function process(): bool
    {
        $owner = OrderCustomer::find($this->data['customer']);
        if ($owner === null) return false;

        $from = InventoryTourRepository::getComponent($this->data['from_component'], $this->data['from_id']);
        $to = InventoryTourRepository::getComponent($this->data['to_component'], $this->data['to_id']);

        if ($from === null || $to === null) return false;
        if ($owner->order->tour_id !== $from->get()->tour_id || $owner->order->tour_id !== $to->get()->tour_id) return false;

        $oFrom = $from->getOrderComponent($owner);
        if ($oFrom === null) return false;

        $oFrom->delete();
        $to->grantToCustomer($owner);

        return true;
    }
}
