<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Admin\TableRequest;
use App\Http\Requests\Api\Admin\Order\UnknownTravellerRequest;
use App\Http\Requests\Api\Admin\OrderRequest;
use App\Models\Order\Order;
use App\Repository\Model\Order\OrderRepository;

class OrderController extends ApiController
{
    public function getOrderStatus(Order $order) {
        return $order->status;
    }

    public function getOverview(TableRequest $request)
    {
        return response()->json(OrderRepository::getOrdersOverview(($request->historic ?? true)));
    }

    public function getRoomingInformation(Order $order)
    {
        return $order->rooming->getRoomingData();
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

    public function resendOrderConfirmation(OrderRequest $request)
    {
        $order = $request->getOrder();
        $success = $order->repository->mailer()->sendBookingConfirmation();
        if ($success) {
            return response()->json(['success' => true, 'message' => 'Successfully resent the booking confirmation email']);
        } else {
            return response()->json(['success' => false, 'message' => 'Mailing is currently disabled on this system']);
        }
    }
}
