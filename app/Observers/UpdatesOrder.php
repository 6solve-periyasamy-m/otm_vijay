<?php

namespace App\Observers;

use App\Models\Order\Order;
use Exception;
use Log;

trait UpdatesOrder
{
    protected function updateOrder(Order $order): void
    {
        try {
            $order->repository->refresh();
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}