<?php

namespace App\Observers\Order\Adjustment;

use App\Models\Order\Adjustment\OrderCustomerAdjustment;
use App\Models\Order\Order;
use App\Observers\OrderUpdateObserver;
use Illuminate\Database\Eloquent\Model;

class OrderCustomerAdjustmentObserver extends OrderUpdateObserver
{

    protected function getOrder(Model $model): Order|null
    {
        /**
         * @var OrderCustomerAdjustment $model
         */
        return $model->orderCustomer->order;
    }
}
