<?php

namespace App\Observers\Order;

use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Observers\OrderUpdateObserver;
use Illuminate\Database\Eloquent\Model;

class OrderCustomerObserver extends OrderUpdateObserver
{

    protected function getOrder(Model $model): Order|null
    {
        /**
         * @var OrderCustomer $model
         */
        return $model->order;
    }
}
