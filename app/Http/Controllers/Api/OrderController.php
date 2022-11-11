<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\ApiController;
use App\Models\Order\Order;
use App\Repository\Model\Order\OrderRepository;

class OrderController extends ApiController
{
    public function getOrderStatus(Order $order) {
        return $order->status;
    }
    public function getOverview()
    {
        return response()->json(OrderRepository::getOrdersOverview());
    }
}
