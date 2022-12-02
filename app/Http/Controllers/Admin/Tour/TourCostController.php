<?php

namespace App\Http\Controllers\Admin\Tour;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Tour\TourCostRequest;
use App\Models\AdditionalCost;
use App\Models\Tour\Tour;

class TourCostController extends Controller
{
    public function store(TourCostRequest $request, Tour $tour)
    {
        $tour->costs()->save(new AdditionalCost($request->getData()));
        return redirect()->route('tours.costing', ['tour' => $tour,]);
    }

    public function update(TourCostRequest $request, Tour $tour, AdditionalCost $cost)
    {
        $cost->update($request->getData());
        $cost->save();
        return redirect()->route('tours.costing', ['tour' => $tour,]);
    }

    public function destroy(Tour $tour, AdditionalCost $cost)
    {
        $cost->delete();
        return redirect()->route('tours.costing', ['tour' => $tour,]);
    }
}
