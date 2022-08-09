<?php

namespace App\Http\Controllers\Admin\Quote;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Quote\QuoteInstallmentRequest;
use App\Models\Quote\Quote;
use App\Models\Quote\QuoteInstallment;

class QuoteInstallmentController extends Controller
{
    public function store(QuoteInstallmentRequest $request, Quote $quote)
    {
        $quote->repository->addInstallment($request->getDueDate(), $request->amount);
        return redirect()->route('quotes.view', ['quote' => $quote,]);
    }

    public function update(QuoteInstallmentRequest $request, Quote $quote, QuoteInstallment $installment)
    {
        $installment->update($request->getData());
        return redirect()->route('quotes.view', ['quote' => $quote,]);
    }

    public function delete(Quote $quote, QuoteInstallment $installment)
    {
        $installment->delete();
        return redirect()->route('quotes.view', ['quote' => $quote,]);
    }

}
