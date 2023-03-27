<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\Admin\Order\UnknownTravellerRequest;
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

    public function generateUnknown(UnknownTravellerRequest $request, ?Order $order)
    {
        if (!empty($order) && isset($order->booking_reference)) {
            $firstName = "Unknown Traveller";
            $lastName = $order->booking_reference;
        } else {
            $firstName = "Unknown";
            $lastName = "Traveller";
        }
        $customers = [];
        for ($x = 0; $x < ($request->count ?? 1); $x++) {
            $customer = OrderRepository::generateGenericCustomer($firstName, $lastName);
            $customers[] = ['id' => $customer->id, 'text' => $customer->first_name . ' ' . $customer->last_name,];
        }
        return response()->json(['success' => true, 'data' => $customers,]);
    }
}
