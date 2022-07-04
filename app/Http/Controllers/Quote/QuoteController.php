<?php

namespace App\Http\Controllers\Quote;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Quote\BespokeQuoteRequest;
use App\Models\Quote\Quote;
use App\Models\Tour\Tour;
use App\Repository\Model\Quote\QuoteRepository;
use App\Repository\Model\Tour\TourRepository;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index()
    {
        $quotes = Quote::with('leadTraveller', 'leadTraveller.prospect', 'tour')->get();
        return view('pages.admin.quote.table', ['quotes' => $quotes,]);
    }

    public function create(?Tour $tour = null)
    {
        if (isset($tour)) {
            return view('pages.admin.quote.create.basic', ['tour' => $tour,]);
        }
        return view('pages.admin.quote.create.bespoke');
    }

    public function storeBespoke(BespokeQuoteRequest $request)
    {
        $tour = TourRepository::create($request->getTourDetails());
        $quote = QuoteRepository::create($tour, $request->getCustomer());
        return redirect()->route('quotes.view', ['quote' => $quote,]);
    }

    public function storeBasic(Request $request)
    {
        // TODO: Stub (Generated)
    }

    public function view(Quote $quote)
    {
        return redirect()->route('quotes.all');
    }

    public function edit(Quote $quote)
    {
        // TODO: Stub (Generated)
    }

    public function update(Request $request, Quote $quote)
    {
        // TODO: Stub (Generated)
    }

    public function delete(Quote $quote)
    {
        // TODO: Stub (Generated)
    }
}
