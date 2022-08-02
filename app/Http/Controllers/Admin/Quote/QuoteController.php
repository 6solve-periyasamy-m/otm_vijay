<?php

namespace App\Http\Controllers\Admin\Quote;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Quote\ConversionRequest;
use App\Http\Requests\Admin\Quote\CreateBasicQuoteRequest;
use App\Http\Requests\Admin\Quote\CreateBespokeQuoteRequest;
use App\Http\Requests\Admin\Quote\QuoteEditRequest;
use App\Http\Requests\Admin\Quote\StartConversionRequest;
use App\Models\Helper\QuoteStatus;
use App\Models\Quote\Quote;
use App\Models\Quote\SentQuote;
use App\Models\Tour\Tour;
use App\Repository\Model\Quote\QuoteRepository;
use App\Repository\Storage\ConvertedCustomer;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index()
    {
        $quotes = Quote::with('leadTraveller', 'tour')->get();
        return view('pages.admin.quote.table', ['quotes' => $quotes,]);
    }

    public function create(?Tour $tour = null)
    {
        if (isset($tour)) {
            return view('pages.admin.quote.create.basic', ['tour' => $tour,]);
        }
        return view('pages.admin.quote.create.bespoke');
    }

    public function storeBespoke(CreateBespokeQuoteRequest $request)
    {
        $quote = QuoteRepository::createBespoke($request->getCustomer(), $request->cost, $request->getTourDetails());
        return redirect()->route('quotes.view', ['quote' => $quote,]);
    }

    public function storeBasic(CreateBasicQuoteRequest $request, Tour $tour)
    {
        $quote = QuoteRepository::createFromTour($tour, $request->getCustomer(), $request->getDataset());
        return redirect()->route('quotes.view', ['quote' => $quote,]);
    }

    public function conversion(StartConversionRequest $request, Quote $quote)
    {
        if ($request->travelling == 0 && $request->paying == 0) {
            $order = $quote->repository->convertToOrder(new ConvertedCustomer($quote->leadTraveller->customer, $quote->leadTraveller->travelling, $quote->leadTraveller->paying));
            return redirect()->route('orders.view', ['order' => $order,]);
        }
        return view('pages.admin.quote.convert', ['quote' => $quote, 'travelling' => $request->travelling, 'paying' => $request->paying,]);
    }

    public function document(string $reference, int $paying, int $travelling)
    {
        $quote = QuoteRepository::getFromReference($reference);
        if (!isset($quote)) abort(404);
        return $quote->repository->getResponseStream($quote->repository->generateSent('test', $paying, $travelling));
    }

    public function convert(ConversionRequest $request, Quote $quote)
    {
        $order = $quote->repository->convertToOrder(new ConvertedCustomer($quote->leadTraveller->customer, $quote->leadTraveller->travelling, $quote->leadTraveller->paying), $request->getCustomers());
        return redirect()->route('orders.view', ['order' => $order,]);
    }

    public function send(StartConversionRequest $request, Quote $quote)
    {
        $quote->repository->resend($quote->repository->generateSent($quote->leadTraveller->email, $request->paying, $request->travelling));
        return redirect()->route('quotes.view', ['quote' => $quote,]);
    }

    public function resend(Quote $quote, SentQuote $sent)
    {
        $quote->repository->resend($sent);
        return redirect()->route('quotes.view', ['quote' => $quote,]);
    }

    public function rebuild(Quote $quote, SentQuote $sent)
    {
        $newQuote = QuoteRepository::deserializeAndSave($sent);
        $quote->repository->update(['quote_status' => QuoteStatus::CLOSED->value]);
        return redirect()->route('quotes.view', ['quote' => $newQuote,]);
    }

    public function view(Quote $quote)
    {
        return view('pages.admin.quote.view', ['quote' => $quote,]);
    }

    public function edit(Quote $quote)
    {
        return view('pages.admin.quote.edit', ['quote' => $quote,]);
    }

    public function update(QuoteEditRequest $request, Quote $quote)
    {
        $quote->repository->update($request->getDataset());
        $quote->repository->updateLead($request->getCustomerDataset());
        return redirect()->route('quotes.view', ['quote' => $quote,]);
    }

    public function delete(Quote $quote)
    {
        // TODO: Stub (Generated)
    }
}
