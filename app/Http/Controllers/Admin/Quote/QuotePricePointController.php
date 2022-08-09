<?php

namespace App\Http\Controllers\Admin\Quote;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Quote\QuotePricePointRequest;
use App\Models\Quote\Quote;
use App\Models\Quote\QuotePricePoint;

class QuotePricePointController extends Controller
{
    public function store(QuotePricePointRequest $request, Quote $quote)
    {
        $quote->repository->addPricePoint($request->quantity, $request->cost);
        return redirect()->route('quotes.view', ['quote' => $quote,]);
    }

    public function update(QuotePricePointRequest $request, Quote $quote, QuotePricePoint $pricePoint)
    {
        $pricePoint->update(['quantity' => $request->quantity, 'price_per_person' => $request->cost]);
        return redirect()->route('quotes.view', ['quote' => $quote,]);
    }

    public function delete(Quote $quote, QuotePricePoint $pricePoint)
    {
        $pricePoint->delete();
        return redirect()->route('quotes.view', ['quote' => $quote,]);
    }
}
