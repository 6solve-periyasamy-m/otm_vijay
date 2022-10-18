<?php

namespace App\Http\Controllers\Admin\Quote;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Quote\EditComponentRequest;
use App\Models\Quote\Quote;
use App\Repository\Abstracts\QuoteComponentRepository;

class QuoteComponentController extends Controller
{
    public function add(Quote $quote)
    {
        return view('pages.admin.quote.components.add', ['quote' => $quote,]);
    }

    public function edit(Quote $quote, string $type, $id)
    {
        if (!is_numeric($id)) abort(403);
        $component = QuoteComponentRepository::getComponent($type, (int)$id);
        if (!isset($component)) abort(402);
        return view('pages.admin.quote.components.edit', ['quoteComponent' => $component,]);
    }

    public function update(EditComponentRequest $request, Quote $quote, string $type, $id)
    {
        if (!is_numeric($id)) abort(404);
        $component = QuoteComponentRepository::getComponent($type, (int)$id);
        if (!isset($component)) abort(404);
        $component->update($request->getData());
        return redirect()->route('quotes.view', ['quote' => $quote]);
    }

    public function unlink(Quote $quote)
    {
        $quote->repository->update(['tour_id' => null,]);
        return redirect()->route('quotes.view', ['quote' => $quote,]);
    }

    public function delete(Quote $quote, string $type, int $id)
    {
        $component = QuoteComponentRepository::getComponent($type, $id);
        if (!isset($component)) abort(404);
        $component->delete();
        return redirect()->route('quotes.view', ['quote' => $quote,]);
    }
}
