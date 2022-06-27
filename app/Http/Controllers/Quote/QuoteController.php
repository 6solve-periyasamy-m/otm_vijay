<?php

namespace App\Http\Controllers\Quote;

use App\Http\Controllers\Controller;
use App\Models\Quote\Quote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index()
    {
        $quotes = Quote::with('leadTraveller', 'leadTraveller.prospect', 'tour')->get();
        return view('pages.admin.quote.table', ['quotes' => $quotes,]);
    }

    public function create()
    {
        // TODO: Stub (Generated)
    }

    public function store(Request $request)
    {
        // TODO: Stub (Generated)
    }

    public function view(Quote $quote)
    {
        // TODO: Stub (Generated)
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
