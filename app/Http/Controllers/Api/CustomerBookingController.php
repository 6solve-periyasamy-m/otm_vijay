<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity\ActivityInventoryTourUpgrade;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Models\Booking\Component\BookingActivity;
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
        if (!isset($from) || $from->traveller->booking->id !== $booking->id) return response()->json(['success' => false, 'message' => 'That activity does not exist on that booking']);
        if ($request->input('upgrade_id') == 0) {
            $parent = $from->tourComponent->parent();
            $toUpgrade = $parent->upgrades()->first();
            $to = $parent->upgrades()->first()->base;
        } else {
            $toUpgrade = ActivityInventoryTourUpgrade::find($request->input('upgrade_id'));
            $to = $toUpgrade->upgrade;
        }
        if (!isset($to)
            || !$to->is_bookable
            || $to->tour_id !== $booking->tour_id
            || !$from->tourComponent->repository->onUpgradeTree($toUpgrade->repository))
            return response()->json(['success' => false, 'message' => 'That upgrade does not exist on that activity tree']);
        try {
            $success = $booking->repository->upgradeActivityForAll($from->tourComponent, $to);
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
        $customer = BookingTraveller::find($request->customer);
        if (!isset($customer)) return response()->json(['success' => false, 'message' => 'That traveller does not exist on that booking']);
        if ($booking->lead_traveller_id == $customer->id) return response()->json(['success' => false, 'message' => 'Cannot remove Lead Booker from Order']);
        $success = $customer->repository->delete();
        if (!$success) return response()->json(['success' => false, 'message' => 'Failed to remove that traveller']);
        return response()->json(['success' => true, 'message' => 'Customer Removed Successfully']);
    }
}
