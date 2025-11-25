<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\Tour\TourCostRequest;
use App\Models\Location\Currency;
use App\Models\Tour\Tour;
use Illuminate\Http\JsonResponse;
use Settings;

class TourController extends ApiController
{
    public function getTourCost(TourCostRequest $request): JsonResponse
    {
        $tour = Tour::where('booking_form_url', '=', $request->package_name)->first();
        if ($tour === null) {
            return response()->json(['success' => false, 'message' => 'Cannot find package specified']);
        }
        if (!empty($request->currency)) {
            $currency = Currency::where('code', '=', $request->currency)->first();
            if ($currency === null) {
                return response()->json(['success' => false, 'message' => 'Cannot find currency specified']);
            }
        } else {
            $currency = Settings::currency();
        }
        if ($currency?->id !== Settings::currency()?->id) {
            $rate = Settings::getConversionRate(Settings::currency(), $currency);
            if ($rate === null) {
                return response()->json(['success' => false, 'message' => 'No FX rate set for currency specified']);
            }
        } else {
            $rate = 1;
        }
        $cost = sigfig($tour->base_price_per_person * $rate);
        if (flag('booking.round_to_five')) {
            $cost = round_to_five($cost);
        }
        return response()->json([
            'success' => true,
            'message' => 'Successfully found package and cost',
            'currency' => $currency?->code,
            'rate' => $rate,
            'price' => $cost,
        ]);
    }
}
