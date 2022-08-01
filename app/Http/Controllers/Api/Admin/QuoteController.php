<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\Admin\Quote\AddComponentRequest;
use App\Http\Requests\Api\Admin\Quote\QuoteCostRequest;
use App\Models\Quote\Quote;
use App\Repository\Abstracts\InventoryRepository;

class QuoteController extends ApiController
{
    public function getCost(QuoteCostRequest $request, Quote $quote)
    {
        if ($quote->leadTraveller->travelling) {
            if ($quote->leadTraveller->paying) {
                $request->paying++;
            } else {
                $request->travelling++;
            }
        }
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
            'margin' => $costToCustomer > 0 && $costToCompany > 0 ? sigfig((($costToCustomer - $costToCompany) / $costToCustomer)*100) : ($costToCustomer <= 0 ? 0 : 100),
            'ctc' => $costToCompany,
            'f_ctc' => f_currency($costToCompany),
        ];
        return response()->json($data);
    }

    public function addComponents(AddComponentRequest $request, Quote $quote, string $component)
    {
        foreach ($request->ids as $id) {
            InventoryRepository::getComponent($component, $id)?->addToQuote($quote, $request->type);
        }
        return response()->json(['success' => true, 'message' => 'Components added successfully']);
    }
}
