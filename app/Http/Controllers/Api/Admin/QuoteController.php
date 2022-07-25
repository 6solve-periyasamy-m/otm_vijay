<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\Admin\Quote\QuoteCostRequest;
use App\Models\Quote\Quote;

class QuoteController extends ApiController
{
    public function getCost(QuoteCostRequest $request, Quote $quote)
    {
        $customers = $request->paying + $request->travelling;
        $cost = $quote->repository->getPricePerPerson($request->paying);
        if ($cost === null && $request->paying > 0) {
            return response()->json(['success' => false, 'message' => 'No price point exists for that few travellers']);
        }
        $cost = $request->paying == 0 ? 0 : $cost->price_per_person;
        $purchasePrice = $quote->repository->getPurchaseTotal();
        $costToCompany = $purchasePrice * ($request->paying + $request->travelling);
        $costToCustomer = $cost * $request->paying;
        $profit = $costToCustomer - $costToCompany;
        $data = [
            'success' => true,
            'price' => $cost,
            'f_price' => f_currency($cost),
            'total' => $costToCustomer,
            'f_total' => f_currency($costToCustomer),
            'profit' => $cost - $purchasePrice,
            'f_profit' => f_currency($customers == 0 ? 0 : $cost - $purchasePrice),
            'profit_total' => $profit,
            'f_profit_total' => f_currency($profit),
            'margin' => $purchasePrice > 0 && $costToCompany > 0 ? sigfig(($costToCustomer / $costToCompany)*100) : ($costToCustomer <= 0 ? 0 : 100),
            'ctc' => $costToCompany,
            'f_ctc' => f_currency($costToCompany),
        ];
        return response()->json($data);
    }
}
