<?php

namespace App\Http\Controllers\Admin\Quote;

use App\Http\Controllers\Controller;
use App\Models\Helper\QuoteStatus;
use App\Models\Quote\Quote;

class QuoteStatusController extends Controller
{

    public function changes(Quote $quote)
    {
        $quote->repository->update(['quote_status' => QuoteStatus::CHANGES,]);
        return redirect()->route('quotes.view', ['quote' => $quote,]);
    }

    public function approve(Quote $quote)
    {
        $quote->repository->update(['quote_status' => QuoteStatus::APPROVED,]);
        return redirect()->route('quotes.view', ['quote' => $quote,]);
    }

    public function close(Quote $quote)
    {
        $quote->repository->update(['quote_status' => QuoteStatus::CLOSED,]);
        return redirect()->route('quotes.view', ['quote' => $quote,]);
    }

}
