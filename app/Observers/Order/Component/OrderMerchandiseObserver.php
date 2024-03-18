<?php

namespace App\Observers\Order\Component;

use App\Models\Order\Component\OrderMerchandise;
use App\Models\Order\Order;
use App\Observers\OrderUpdateObserver;
use Illuminate\Database\Eloquent\Model;

class OrderMerchandiseObserver extends OrderUpdateObserver
{
    protected function getOrder(Model $model): Order|null
    {
        /** @var OrderMerchandise $model */
        return $model->orderCustomer->order;
    }
}
