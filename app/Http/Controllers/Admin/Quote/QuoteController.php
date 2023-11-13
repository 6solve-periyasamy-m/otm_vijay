<?php

namespace App\Http\Controllers\Admin\Quote;

use App\Exceptions\MailDisabledException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Quote\ConversionRequest;
use App\Http\Requests\Admin\Quote\CreateBasicQuoteRequest;
use App\Http\Requests\Admin\Quote\CreateBespokeQuoteRequest;
use App\Http\Requests\Admin\Quote\QuoteEditRequest;
use App\Http\Requests\Admin\Quote\StartConversionRequest;
use App\Http\Requests\Admin\TableRequest;
use App\Models\Helper\QuoteStatus;
use App\Models\Quote\Quote;
use App\Models\Quote\SentQuote;
use App\Models\Tour\Tour;
use App\Repository\Model\Quote\QuoteRepository;
use App\Repository\Storage\ConvertedCustomer;

class QuoteController extends Controller
{
    public function index(TableRequest $request)
    {
        if ($request->historic ?? (setting('system.historic', 6) < 0)) {
            $quotes = Quote::with('leadTraveller', 'tour')->get();
        } else {
            $quotes = Quote::with('leadTraveller', 'tour')->whereDate('date_to', '>', now()->subMonths(setting('system.historic', 6)))->get();
        }
        return view('pages.admin.quote.table', ['quotes' => $quotes, 'historic' => $request->historic ?? false,]);
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
        \Log::info('Going through');
        $paying = $request->paying + ($quote->leadTraveller->paying ? 1 : 0);
        $pricePoint = $quote->repository->getPricePerPerson($request->paying + ($quote->leadTraveller->paying ? 1 : 0));
        if (!isset($pricePoint)) {
            \Log::info('Failed');
            return back()->withErrors(['msg' => "No price points exist for {$paying} paying travellers",]);
        }
        if ($request->travelling == 0 && $request->paying == 0) {
            $order = $quote->repository->convertToOrder(new ConvertedCustomer($quote->leadTraveller->customer, $quote->leadTraveller->paying, $quote->leadTraveller->travelling), [], $request->doEmail());
            return redirect()->route('orders.view', ['order' => $order,]);
        }
        return view('pages.admin.quote.convert', ['quote' => $quote, 'travelling' => $request->travelling, 'paying' => $request->paying, 'email' => $request->doEmail()]);
    }

    public function document(Quote $quote, SentQuote $sent)
    {
        return $quote->repository->getResponseStream($sent);
    }

    public function preview(StartConversionRequest $request, Quote $quote)
    {
        $paying = $request->paying + ($quote->leadTraveller->paying ? 1 : 0);
        $pricePoint = $quote->repository->getPricePerPerson($request->paying + ($quote->leadTraveller->paying ? 1 : 0));
        if (!isset($pricePoint)) {
            return back()->withErrors(['msg' => "No price points exist for {$paying} paying travellers",]);
        }
        return $quote->repository->getResponseStream($quote->repository->makeSent($quote->leadTraveller->email, $request->paying, $request->travelling));
    }

    public function convert(ConversionRequest $request, Quote $quote)
    {
        $order = $quote->repository->convertToOrder(new ConvertedCustomer($quote->leadTraveller->customer, $quote->leadTraveller->paying, $quote->leadTraveller->travelling), $request->getCustomers(), $request->doEmail());
        return redirect()->route('orders.view', ['order' => $order]);
    }

    public function send(StartConversionRequest $request, Quote $quote)
    {
        $paying = $request->paying + ($quote->leadTraveller->paying ? 1 : 0);
        $pricePoint = $quote->repository->getPricePerPerson($request->paying + ($quote->leadTraveller->paying ? 1 : 0));
        if (!isset($pricePoint)) {
            return back()->withErrors(['msg' => "No price points exist for {$paying} paying travellers",]);
        }
        try {
            $quote->repository->resend($quote->repository->generateSent($quote->leadTraveller->email, $request->paying, $request->travelling));
        } catch (MailDisabledException) {
            return back()->withErrors(['msg' => 'Emails are not enabled on this system']);
        }
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

    public function costing(StartConversionRequest $request, Quote $quote)
    {
        $paying = $quote->leadTraveller->paying ? 1 : 0;
        $travelling = $paying > 0 ? 0 : 1;
        return view('pages.admin.quote.costing', ['quote' => $quote, 'paying' => $request->paying + $paying, 'travelling' => $request->travelling + $travelling,]);
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
        $quote->delete();
        return redirect()->route('quotes.all');
    }
}
