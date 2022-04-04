<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\ApiController;
use App\Models\Order\Order;

class OrderController extends ApiController
{
    public function getOrderStatus(Order $order) {
        return $order->getStatus();
    }
}
