<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\ApiController;
use App\Http\Gateways\StripeGateway;
use App\Http\Requests\Booking\Simple\BookingRequest;
use App\Models\Booking\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Settings;

class SimpleBookingController extends ApiController
{
    public function getStripeSecret(Request $request): JsonResponse
    {
        $booking = Booking::where('token', '=', $request->token)->first();
        if ($booking === null) { return response()->json(['success' => false, 'message' => 'Requested booking was not for the selected tour'], 422); }
        $rate = Settings::getConversionRate(Settings::currency(), $booking->currency?->code) ?? 1.0;
        if ($request->full ?? false) {
            $amount = $booking->repository->getTotalCost() * $rate;
        } else {
            $amount = $booking->repository->getDueTodayAmount() * $rate;
        }
        $keys = $booking->repository->getStripeKey($amount);
        return response()->json(['success' => true, 'intent' => $keys['intent'], 'checkoutSessionClientSecret' => $keys['secret'],]);
    }

    public function assignPaymentMethod(Request $request)
    {
        $gateway = \Gateway::getPaymentGateway('stripe');
        if ($gateway instanceof StripeGateway) {
            $gateway->attachPaymentMethodToIntention($request->secret, $request->paymentMethod);
        }
    }
}
