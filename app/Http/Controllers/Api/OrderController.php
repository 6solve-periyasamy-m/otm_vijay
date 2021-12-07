<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\ApiController;
use App\Models\Order;
use App\Repository\OrderRepository;
use Illuminate\Support\Facades\Log;

use App\Models\Event;
use App\Models\Tour;
use App\Models\OrderCustomer;

class OrderController extends ApiController
{
    public function getOrderStatus(Order $order) {
        return OrderRepository::getOrderStatus($order);
    }
}
