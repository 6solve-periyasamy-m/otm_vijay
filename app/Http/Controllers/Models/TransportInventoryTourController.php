<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\TransportInventoryTour;
use Illuminate\Http\Request;

class TransportInventoryTourController extends Controller
{

    public function index(Tour $tour)
    {
        return view('pages.models.transport_inventory_tours.table', ['tour' => $tour, 'transportInventoryTours' => TransportInventoryTour::all(),]);
    }

    public function create(Tour $tour)
    {
        return view('pages.models.transport_inventory_tours.create', ['tour' => $tour,]);
    }

    public function store(Request $request, Tour $tour)
    {
        $request->validate(TransportInventoryTour::getValidationRules());
        $transportInventoryTour = TransportInventoryTour::make([
            'transport_inventory_id' => $request->input('transport_inventory_id'),
            'tour_component_type' => $request->input('tour_component_type'),
            'tour_sales_price' => $request->input('tour_sales_price'),
        ]);
        $tour->transportInventoryTours()->save($transportInventoryTour);
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function view(Tour $tour, TransportInventoryTour $transportInventoryTour)
    {
        return view('pages.models.transport_inventory_tours.view', ['tour' => $tour, 'transportInventoryTour' => $transportInventoryTour,]);
    }

    public function edit(Tour $tour, TransportInventoryTour $transportInventoryTour)
    {
        return view('pages.models.transport_inventory_tours.update', ['tour' => $tour, 'transportInventoryTour' => $transportInventoryTour,]);
    }

    public function update(Request $request, Tour $tour, TransportInventoryTour $transportInventoryTour)
    {
        $request->validate(TransportInventoryTour::getValidationRules());
        $transportInventoryTour->update([
            'transport_inventory_id' => $request->input('transport_inventory_id'),
            'tour_component_type' => $request->input('tour_component_type'),
            'tour_sales_price' => $request->input('tour_sales_price'),
        ]);
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function destroy(Tour $tour, TransportInventoryTour $transportInventoryTour)
    {
        $transportInventoryTour->delete();
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }
}
