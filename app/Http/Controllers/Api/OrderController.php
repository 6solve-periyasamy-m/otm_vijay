<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\ApiController;
use App\Models\Customer\Customer;
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

    public function getRoomingInformation(Order $order)
    {
        return $order->repository->getRoomingData();
    }

    public function generateUnknown(?Order $order)
    {
        if (!empty($order) && isset($order->booking_reference)) {
            $firstName = "Unknown Traveller";
            $lastName = $order->booking_reference;
        } else {
            $firstName = "Unknown";
            $lastName = "Traveller";
        }
        $customer = OrderRepository::generateGenericCustomer($firstName, $lastName);
        return response()->json(['success' => true, 'id' => $customer->id, 'text' => $customer->first_name . ' ' . $customer->last_name,]);
    }
}
