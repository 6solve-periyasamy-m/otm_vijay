<?php

namespace App\Observers\Order\Payment;

use App\Models\Order\Order;
use App\Models\Order\Payment\Payment;
use App\Observers\OrderUpdateObserver;
use Illuminate\Database\Eloquent\Model;

class PaymentObserver extends OrderUpdateObserver
{

    protected function getOrder(Model $model): Order|null
    {
        /**
         * @var Payment $model
         */
        return $model->order;
    }
}
