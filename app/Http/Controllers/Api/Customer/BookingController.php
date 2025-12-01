<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Booking\Simple\SetupBookingRequest;
use App\Http\Requests\BookingOverviewRequest;
use App\Http\Requests\TourOverviewRequest;
use App\Models\Booking\BookingTraveller;
use App\Repository\Model\Booking\BookingRepository;
use Illuminate\Http\JsonResponse;

class BookingController extends ApiController
{
    public function overview(TourOverviewRequest $request): JsonResponse
    {
        $tour = $request->getTour();
        if ($tour === null || !$tour->is_active) {
            return response()->json(['success' => false, 'message' => 'A tour with that URL does not exist.',], 422);
        }
        return response()->json([
            'success' => true,
            'booking' => [
                'tour' => $tour->repository->getDataForBooking($request->currency),
            ]
        ]);
    }

    public function booking(BookingOverviewRequest $request): JsonResponse
    {
        $tour = $request->getTour();
        if ($tour === null || !$tour->is_active) {
            return response()->json(['success' => false, 'message' => 'A tour with that URL does not exist.',], 422);
        }
        $booking = $request->getBooking();
        if ($booking === null) {
            return response()->json(['success' => false, 'message' => 'A booking with that token does not exist.',], 422);
        }
        if ($booking->tour_id !== $tour->id) {
            return response()->json(['success' => false, 'message' => 'The token does not match this tour',], 422);
        }
        return response()->json([
            'success' => true,
            'booking' => $booking->repository->getSimpleData(),
        ]);
    }

    public function setup(SetupBookingRequest $request): JsonResponse
    {
        $tour = $request->getTour();
        if ($tour === null || !$tour->is_active) {
            return response()->json(['success' => false, 'message' => 'Could not find selected tour'], 404);
        }
        $booking = BookingRepository::create($tour, new BookingTraveller([
            'first_name' => $request->name,
            'email_address' => $request->email,
        ]));

        return response()->json(['success' => true, 'booking' => $booking->repository->getSimpleData(),]);
    }
}