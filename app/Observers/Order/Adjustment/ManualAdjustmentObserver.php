<?php

namespace App\Observers\Order\Adjustment;

use App\Models\Order\Adjustment\ManualAdjustment;
use App\Models\Order\Order;
use App\Observers\OrderUpdateObserver;
use Illuminate\Database\Eloquent\Model;

class ManualAdjustmentObserver extends OrderUpdateObserver
{

    protected function getOrder(Model $model): Order|null
    {
        /**
         * @var ManualAdjustment $model
         */
        return $model->order;
    }
}
