<?php

namespace App\Http\Controllers\Admin\Quote;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Quote\QuoteSectionRequest;
use App\Models\Quote\Quote;
use App\Models\Quote\QuoteSection;

class QuoteSectionController extends Controller
{
    public function create(Quote $quote)
    {
        return view('pages.admin.quote.section.form', ['quote' => $quote,]);
    }

    public function store(QuoteSectionRequest $request, Quote  $quote)
    {
        $section = QuoteSection::make($request->getData());
        if ($request->has('image') && $request->image !== null) {
            $section->image_url = store_file($request->image);
        }
        $quote->sections()->save($section);
        return redirect()->route('quotes.view', ['quote' => $quote,]);
    }

    public function edit(Quote $quote, QuoteSection $section)
    {
        return view('pages.admin.quote.section.form', ['quote' => $quote, 'section' => $section,]);
    }

    public function update(QuoteSectionRequest $request, Quote  $quote, QuoteSection $section)
    {
        $section->update($request->getData());
        if ($request->has('image') && $request->image !== null) {
            $section->image_url = store_file($request->image);
        }
        $section->save();
        return redirect()->route('quotes.view', ['quote' => $quote,]);
    }

    public function delete(Quote $quote, QuoteSection $section)
    {
        $section->delete();
        return redirect()->route('quotes.view', ['quote' => $quote,]);
    }
}
