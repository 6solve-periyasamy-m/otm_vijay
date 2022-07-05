<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\Admin\Quote\QuoteCostRequest;
use App\Models\Quote\Quote;

class QuoteController extends ApiController
{
    public function getCost(QuoteCostRequest $request, Quote $quote)
    {
        $cost = $quote->repository->getPricePerPerson($request->count);
        if ($cost === null) {
            return response()->json(['success' => false, 'message' => 'No price point exists for that few travellers']);
        }
        $data = [
            'success' => true,
            'price' => $cost->price_per_person,
            'f_price' => f_currency($cost->price_per_person),
            'total' => $cost->price_per_person * $request->count,
            'f_total' => f_currency($cost->price_per_person * $request->count),
        ];
        return response()->json($data);
    }
}
