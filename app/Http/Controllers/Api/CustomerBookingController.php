<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity\ActivityInventoryTourUpgrade;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingActivity;
use App\Models\Customer\Customer;
use App\Repository\ActivityComponentRepository;
use App\Repository\CustomerBookingRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Log;
use Throwable;

class CustomerBookingController extends Controller
{
    public function upgradeActivity(Request $request, string $token): JsonResponse
    {
        $booking = Booking::where('token', $token)->first();
        if (!isset($booking)) return response()->json(['success' => false, 'message' => 'That booking does not exist']);
        $from = BookingActivity::find($request->input('component_id'));
        if (!isset($from) || $from->booking_id !== $booking->id) return response()->json(['success' => false, 'message' => 'That activity does not exist on that booking']);
        if ($request->input('upgrade_id') == 0) {
            $parent = $from->tourComponent->parent();
            $to = $parent->upgrades()->first();
        } else {
            $to = ActivityInventoryTourUpgrade::find($request->input('upgrade_id'));
        }
        if (!isset($to) || !$to->upgrade->is_bookable || $to->upgrade->tour_id !== $booking->tour_id || !ActivityComponentRepository::isOnUpgradeTree($from->tourComponent, $to))
            return response()->json(['success' => false, 'message' => 'That upgrade does not exist on that activity tree']);
        try {
            $success = CustomerBookingRepository::upgradeBookingActivity($booking, $from->tourComponent, $request->input('upgrade_id') == 0 ? $to->base : $to->upgrade);
            if (!$success)
                return response()->json(['success' => false, 'message' => 'Upgrade failed to apply, please try again later']);
            return response()->json(['success' => true, 'message' => 'Upgrade applied successfully']);
        } catch (Throwable $e) {
            Log::error($e);
        }
        return response()->json(['success' => false, 'message' => 'Upgrade failed to apply, please try again later']);
    }

    public function removeCustomer(Request $request, string $token): JsonResponse
    {
        $booking = Booking::where('token', $token)->first();
        if (!isset($booking)) return response()->json(['success' => false, 'message' => 'That booking does not exist']);
        $customer = Customer::find($request->customer);
        if (!isset($customer)) return response()->json(['success' => false, 'message' => 'That customer does not exist on that booking']);
        if ($booking->customer_id == $customer->id) return response()->json(['success' => false, 'message' => 'Cannot remove Lead Booker from Order']);
        $success = CustomerBookingRepository::removeCustomerFromBooking($booking, $customer);
        if (!$success) return response()->json(['success' => false, 'message' => 'That customer does not exist on that booking']);
        return response()->json(['success' => true, 'message' => 'Customer Removed Successfully']);
    }
}
