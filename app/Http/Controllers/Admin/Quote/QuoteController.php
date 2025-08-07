<?php

namespace App\Http\Controllers\Admin\Quote;

use App\Exceptions\MailDisabledException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Quote\ConversionRequest;
use App\Http\Requests\Admin\Quote\CreateBasicQuoteRequest;
use App\Http\Requests\Admin\Quote\StartConversionRequest;
use App\Http\Requests\Admin\TableRequest;
use App\Models\Helper\Enum\QuoteStatus;
use App\Models\Quote\Quote;
use App\Models\Quote\QuoteProspect;
use App\Models\Quote\SentQuote;
use App\Models\Tour\Tour;
use App\Repository\Model\Quote\QuoteRepository;
use App\Repository\Storage\ConvertedCustomer;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;


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
            $is_final_payment_passed = now()->gt($tour->final_payment);
            return view('pages.admin.quote.create.basic', ['tour' => $tour, 'is_final_payment_passed' => $is_final_payment_passed,]);
        }
        return view('pages.admin.quote.form');
    }

    public function storeBasic(CreateBasicQuoteRequest $request, Tour $tour): RedirectResponse
    {
        $quote = QuoteRepository::createFromTour($tour, $request->getCustomer(), $request->getDataset(), $request->getCustomerDataset());
        return redirect()->route('quotes.view', ['quote' => $quote,]);
    }

    public function conversion(StartConversionRequest $request, Quote $quote)
    {
        $paying = $request->paying + ($quote->leadTraveller->paying ? 1 : 0);
        $pricePoint = $quote->repository->getPricePerPerson($paying);
        if (!isset($pricePoint)) {
            return back()->withErrors(['msg' => "No price points exist for {$paying} paying travellers",]);
        }
        if ($request->travelling == 0 && $request->paying == 0) {
            $order = $quote->repository->convertToOrder(new ConvertedCustomer($quote->leadTraveller->customer, $quote->leadTraveller->paying, $quote->leadTraveller->travelling), [], $request->doEmail());
            return redirect()->route('orders.view', ['order' => $order,]);
        }
        return view('pages.admin.quote.convert', ['quote' => $quote, 'travelling' => $request->travelling, 'paying' => $request->paying, 'email' => $request->doEmail()]);
    }

    public function accommodation(Quote $quote)
    {
        return view('pages.admin.quote.accommodation', ['quote' => $quote]);
    }

    public function document(Quote $quote, SentQuote $sent): StreamedResponse
    {
        return $quote->repository->getResponseStream($sent);
    }
 
    public function preview(StartConversionRequest $request, Quote $quote): StreamedResponse
    {
        return $quote->repository->getResponseStream($quote->repository->makeSent($quote->leadTraveller->email, $request->paying, $request->travelling));
    }

    public function convert(ConversionRequest $request, Quote $quote): RedirectResponse
    {
        $order = $quote->repository->convertToOrder(new ConvertedCustomer($quote->leadTraveller->customer, $quote->leadTraveller->paying, $quote->leadTraveller->travelling), $request->getCustomers(), $request->doEmail());
        return redirect()->route('orders.view', ['order' => $order]);
    }

    public function send(StartConversionRequest $request, Quote $quote): RedirectResponse
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

    public function resend(Quote $quote, SentQuote $sent): RedirectResponse
    {
        try {
            $quote->repository->resend($sent);
        } catch (MailDisabledException) {
            return back()->withErrors(['msg' => 'Emails are not enabled on this system']);
        }
        return redirect()->route('quotes.view', ['quote' => $quote,]);
    }

    public function rebuild(Quote $quote, SentQuote $sent): RedirectResponse
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
        return view('pages.admin.quote.form', ['quote' => $quote,]);
    }

    public function delete(Quote $quote): RedirectResponse
    {
        $quote->delete();
        return redirect()->route('quotes.all');
    }

    public function forceDelete(Quote $quote): RedirectResponse
    {
        if (!is_otm()) {
            abort(403);
        }
        $quote->repository->forceDelete();
        return redirect()->route('quotes.all');
    }

    public function deleteProspect(Quote $quote, QuoteProspect $prospect): RedirectResponse
    {
        if ($prospect->is_lead) {
            return back()->withErrors(['msg' => "You can't delete the lead traveller"]);
        }
        $prospect->delete();
        return redirect()->route('quotes.view', ['quote' => $quote]);
    }
}
