<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Booking\Simple\BookingRequest;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Repository\Model\Booking\BookingRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SimpleBookingController extends ApiController
{
    public function setup(BookingRequest $request)
    {
        $tour = $request->getTour();
        if ($tour === null || !$tour->is_active) {
            return response()->json(['success' => false, 'message' => 'Could not find selected tour'], 404);
        }
        $booking = $request->getBooking() ?? BookingRepository::create($tour, new BookingTraveller());
        if ($booking?->tour_id !== $tour->id) {
            return response()->json(['success' => false, 'message' => 'Requested booking was not for the selected tour'], 422);
        }

        return response()->json(['success' => true, 'data' => $booking->repository->getSimpleData(),]);
    }

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
