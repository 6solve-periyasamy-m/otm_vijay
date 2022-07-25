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
        $purchasePrice = $quote->repository->getPurchaseTotal();
        $data = [
            'success' => true,
            'price' => $cost->price_per_person,
            'f_price' => f_currency($cost->price_per_person),
            'total' => $cost->price_per_person * $request->count,
            'f_total' => f_currency($cost->price_per_person * $request->count),
            'profit' => $cost->price_per_person - $purchasePrice,
            'f_profit' => f_currency($cost->price_per_person - $purchasePrice),
            'profit_total' => ($cost->price_per_person - $purchasePrice) * $request->count,
            'f_profit_total' => f_currency(($cost->price_per_person - $purchasePrice) * $request->count),
            'margin' => $purchasePrice > 0 ? sigfig($cost->price_per_person / $purchasePrice) : 100,
        ];
        return response()->json($data);
    }
}
