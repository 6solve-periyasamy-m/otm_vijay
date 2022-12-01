<?php

namespace App\Http\Controllers\Admin\Tour;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Tour\TourCostRequest;
use App\Models\Tour\Tour;
use App\Models\Tour\TourCost;

class TourCostController extends Controller
{
    public function store(TourCostRequest $request, Tour $tour)
    {
        $tour->costs()->save(new TourCost($request->getData()));
        return redirect()->route('tours.costing', ['tour' => $tour,]);
    }

    public function update(TourCostRequest $request, Tour $tour, TourCost $cost)
    {
        $cost->update($request->getData());
        $cost->save();
        return redirect()->route('tours.costing', ['tour' => $tour,]);
    }

    public function destroy(Tour $tour, TourCost $cost)
    {
        $cost->delete();
        return redirect()->route('tours.costing', ['tour' => $tour,]);
    }
}
