<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\ApiController;
use App\Models\Booking\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SimpleBookingController extends ApiController
{
    public function getStripeSecret(Request $request): JsonResponse
    {
        $booking = Booking::where('token', '=', $request->token)->first();
        if ($booking === null) { return response()->json(['success' => false, 'message' => 'Requested booking was not for the selected tour'], 422); }
        if ($request->full ?? false) {
            $amount = $booking->repository->getTotalCost();
        } else {
            $amount = $booking->repository->getDueTodayAmount();
        }
        return response()->json(['success' => true, 'checkoutSessionClientSecret' => $booking->repository->getStripeKey($amount),]);
    }
}
