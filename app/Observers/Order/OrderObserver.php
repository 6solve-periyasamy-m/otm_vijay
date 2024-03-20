<?php

namespace App\Observers\Order;

use App\Models\Order\Order;
use App\Observers\OrderUpdateObserver;
use Illuminate\Database\Eloquent\Model;

class OrderObserver extends OrderUpdateObserver
{

    protected function getOrder(Model $model): Order|null
    {
        /**
         * @var Order $model
         */
        return $model;
    }
}
