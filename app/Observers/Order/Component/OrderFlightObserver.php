<?php

namespace App\Observers\Order\Component;

use App\Models\Order\Component\OrderFlight;
use App\Models\Order\Order;
use App\Observers\OrderUpdateObserver;
use Illuminate\Database\Eloquent\Model;

class OrderFlightObserver extends OrderUpdateObserver
{

    protected function getOrder(Model $model): Order|null
    {
        /** @var OrderFlight $model */
        return $model->orderCustomer->order;
    }
}
