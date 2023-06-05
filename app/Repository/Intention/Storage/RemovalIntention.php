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
class RemovalIntention extends IntentionAction
{
    public static function create(OrderCustomer $customer, InventoryTourRepository $repository): IntentionAction
    {
        return new self([
            'customer' => $customer->id,
            'component' => $repository->getComponentType(),
            'id' => $repository->get()->id
        ]);
    }

    public function process(): bool
    {
        $owner = OrderCustomer::find($this->data['customer']);
        if ($owner === null) return false;

        $component = InventoryTourRepository::getComponent($this->data['component'], $this->data['id']);

        if ($component === null) return false;
        if ($owner->order->tour_id !== $component->get()->tour_id) return false;

        $owned = $component->getOrderComponent($owner);
        if ($owned === null) return false;

        $owned->delete();
        return true;
    }
}
