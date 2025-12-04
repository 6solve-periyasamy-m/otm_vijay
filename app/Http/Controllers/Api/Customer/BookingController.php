<?php

namespace App\Http\Controllers\Api\Customer;

use App\Exceptions\BookingApiException;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Booking\ApiComponentRequest;
use App\Http\Requests\Booking\ApiRoomingRequest;
use App\Http\Requests\Booking\BookingOverviewRequest;
use App\Http\Requests\Booking\Simple\SetupBookingRequest;
use App\Http\Requests\Booking\TourOverviewRequest;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Repository\Model\Booking\BookingRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        $valid = $request->validatePackage();
        if ($valid instanceof JsonResponse) {
            return $valid;
        }
        return response()->json([
            'success' => true,
            'booking' => $request->getBooking()->repository->getSimpleData(),
        ]);
    }

    public function setup(SetupBookingRequest $request): JsonResponse
    {
        $tour = $request->getTour();
        if ($tour === null || !$tour->is_active) {
            return response()->json(['success' => false, 'message' => 'Could not find selected tour'], 404);
        }
        $booking = BookingRepository::createForBookingApi($tour, new BookingTraveller([
            'first_name' => $request->name,
            'email_address' => $request->email,
        ]));

        return response()->json(['success' => true, 'booking' => $booking->repository->getSimpleData(),]);
    }

    public function processRooming(ApiRoomingRequest $request): JsonResponse
    {
        $valid = $request->validatePackage();
        if ($valid instanceof JsonResponse) {
            return $valid;
        }
        $booking = $request->getBooking();
        try {
            $booking->repository->processRoomingFromApi($request->rooming);
        } catch (BookingApiException $e) {
            return response()->json(['success' => false, 'error' => $e->getErrorCode(), 'message' => $e->getMessage()], 400);
        }
        $booking = $booking->refresh();
        return response()->json(['success' => true, 'booking' => $booking->repository->getSimpleData(),]);
    }

    public function processComponents(ApiComponentRequest $request): JsonResponse
    {
        $valid = $request->validatePackage();
        if ($valid instanceof JsonResponse) {
            return $valid;
        }
        $booking = $request->getBooking();
        try {
            $booking->repository->processComponentsFromApi($request->components);
        } catch (BookingApiException $e) {
            return response()->json(['success' => false, 'error' => $e->getErrorCode(), 'message' => $e->getMessage()], 400);
        }
        $booking = $booking->refresh();
        return response()->json(['success' => true, 'booking' => $booking->repository->getSimpleData(),]);
    }

    public function addTraveller(BookingOverviewRequest $request): JsonResponse
    {
        $valid = $request->validatePackage();
        if ($valid instanceof JsonResponse) {
            return $valid;
        }
        $booking = $request->getBooking();
        $booking->repository->addUnknownTraveller();
        return response()->json(['success' => true, 'booking' => $booking->repository->getSimpleData(),]);
    }

    public function removeTraveller(BookingOverviewRequest $request): JsonResponse
    {
        $valid = $request->validatePackage();
        if ($valid instanceof JsonResponse) {
            return $valid;
        }
        $booking = $request->getBooking();
        $booking->repository->removeUnknownTraveller();
        return response()->json(['success' => true, 'booking' => $booking->repository->getSimpleData(),]);
    }

    public function getStripePublishableKey(BookingOverviewRequest $request): JsonResponse
    {
        $valid = $request->validatePackage();
        if ($valid instanceof JsonResponse) {
            return $valid;
        }
        return response()->json(['success' => true, 'publishable' => config('app.gateways.stripe.publishable', null)]);
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