<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\FlightInventoryTour;
use App\Models\Tour;
use Illuminate\Http\Request;

class FlightInventoryTourController extends Controller
{

    public function index(Tour $tour)
    {
        return view('pages.models.flight_inventory_tours.table', ['tour' => $tour, 'flightInventoryTours' => FlightInventoryTour::all(),]);
    }

    public function create(Tour $tour)
    {
        return view('pages.models.flight_inventory_tours.create', ['tour' => $tour,]);
    }

    public function store(Request $request, Tour $tour)
    {
        $request->validate(FlightInventoryTour::getValidationRules());
        $flightInventoryTour = FlightInventoryTour::make([
            'flight_inventory_id' => $request->input('flight_inventory_id'),
            'tour_component_type' => $request->input('tour_component_type'),
            'flight_type' => $request->input('flight_type'),
            'tour_sales_price' => $request->input('tour_sales_price'),
        ]);
        $tour->flightInventoryTours()->save($flightInventoryTour);
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function view(Tour $tour, FlightInventoryTour $flightInventoryTour)
    {
        return view('pages.models.flight_inventory_tours.view', ['tour' => $tour, 'flightInventoryTour' => $flightInventoryTour,]);
    }

    public function edit(Tour $tour, FlightInventoryTour $flightInventoryTour)
    {
        return view('pages.models.flight_inventory_tours.update', ['tour' => $tour, 'flightInventoryTour' => $flightInventoryTour,]);
    }

    public function update(Request $request, Tour $tour, FlightInventoryTour $flightInventoryTour)
    {
        $request->validate(FlightInventoryTour::getValidationRules());
        $flightInventoryTour->update([
            'flight_inventory_id' => $request->input('flight_inventory_id'),
            'tour_component_type' => $request->input('tour_component_type'),
            'flight_type' => $request->input('flight_type'),
            'tour_sales_price' => $request->input('tour_sales_price'),
        ]);
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function destroy(Tour $tour, FlightInventoryTour $flightInventoryTour)
    {
        $flightInventoryTour->delete();
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }
}
