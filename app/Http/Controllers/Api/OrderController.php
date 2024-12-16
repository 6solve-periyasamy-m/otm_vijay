<?php

namespace App\Http\Controllers\Api;
use App\Exceptions\MailFailedException;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Admin\Order\BulkSendReminderRequest;
use App\Http\Requests\Admin\TableRequest;
use App\Http\Requests\Api\Admin\Order\UnknownTravellerRequest;
use App\Http\Requests\Api\Admin\OrderRequest;
use App\Models\Order\Order;
use App\Repository\Model\Order\OrderRepository;
use Exception;
use Illuminate\Http\JsonResponse;

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
        try {
            $success = $order->repository->mailer()->sendBookingConfirmation();
        } catch (MailFailedException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

        if ($success) {
            return response()->json(['success' => true, 'message' => 'Successfully resent the booking confirmation email']);
        }

        return response()->json(['success' => false, 'message' => 'Mailing is currently disabled on this system']);
    }

    /**
     * Bulk send order reminders.
     *
     * Request receives an array of order ids, processes them, and sends each the appropriate order reminder
     *
     * @param BulkSendReminderRequest $request
     * @return JsonResponse
     */
    public function bulkSendOrderReminders(BulkSendReminderRequest $request): JsonResponse
    {
        if (count($request->orders ?? []) === 0) {
            return response()->json(['success' => false, 'message' => "No orders have been requested"]);
        }

        $failed = [];
        $successes = 0;

        foreach ($request->orders as $id) {
            $order = Order::find($id);
            if ($order !== null) {
                if ($order->cancelled) {
                    $failed[] = ['reference' => $order->booking_reference, 'reason' => 'This order is cancelled'];
                    continue;
                }
                $next = $order->next_installment;
                if ($next !== null) {
                    try {
                        $order->repository->mailer(true)->sendReminderMail(null, $next);
                        $successes++;
                    } catch (Exception $e) {
                        $failed[] = ['reference' => $order->booking_reference, 'reason' => $e->getMessage()];
                    }
                } else {
                    $failed[] = ['reference' => $order->booking_reference, 'reason' => 'No Next Installment Available'];
                }
            }
        }

        if ($successes > 0) {
            if (count($failed) > 0) {
                return response()->json(['success' => true, 'message' => 'Completed successfully with some errors', 'errors' => $failed]);
            }
            return response()->json(['success' => true, 'message' => 'Completed successfully']);
        }

        return response()->json(['success' => false, 'message' => 'All mail failed', 'errors' => $failed]);
    }
}
