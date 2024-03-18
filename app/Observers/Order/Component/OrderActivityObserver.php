<?php

namespace App\Observers\Order\Component;

use App\Models\Order\Component\OrderActivity;
use App\Models\Order\Order;
use App\Observers\OrderUpdateObserver;
use Illuminate\Database\Eloquent\Model;

class OrderActivityObserver extends OrderUpdateObserver
{

    protected function getOrder(Model $model): Order|null
    {
        /**
         * @var OrderActivity $model
         */
        return $model->orderCustomer->order;
    }
}
