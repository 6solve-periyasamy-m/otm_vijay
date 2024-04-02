<?php

namespace App\Observers\Order\Component;

use App\Models\Order\Component\OrderTransport;
use App\Models\Order\Order;
use App\Observers\OrderUpdateObserver;
use Illuminate\Database\Eloquent\Model;

class OrderTransportObserver extends OrderUpdateObserver
{
    protected function getOrder(Model $model): Order|null
    {
        /** @var OrderTransport $model */
        return $model->orderCustomer->order;
    }
}
