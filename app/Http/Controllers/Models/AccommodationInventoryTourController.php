<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\AccommodationInventoryTour;
use App\Models\Tour;
use Illuminate\Http\Request;

class AccommodationInventoryTourController extends Controller
{

    public function index(Tour $tour)
    {
        return view('pages.models.accommodation_inventory_tours.table', ['tour' => $tour, 'accommodationInventoryTours' => AccommodationInventoryTour::all(),]);
    }

    public function create(Tour $tour)
    {
        return view('pages.models.accommodation_inventory_tours.create', ['tour' => $tour,]);
    }

    public function store(Request $request, Tour $tour)
    {
        $request->validate(AccommodationInventoryTour::getValidationRules());
        $accommodationInventoryTour = AccommodationInventoryTour::make([
            'accommodation_inventory_id' => $request->input('accommodation_inventory_id'),
            'tour_component_type' => $request->input('tour_component_type'),
            'tour_sales_price' => $request->input('tour_sales_price'),
        ]);
        $tour->accommodationInventoryTours()->save($accommodationInventoryTour);
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function view(Tour $tour, AccommodationInventoryTour $accommodationInventoryTour)
    {
        return view('pages.models.accommodation_inventory_tours.view', ['tour' => $tour, 'accommodationInventoryTour' => $accommodationInventoryTour,]);
    }

    public function edit(Tour $tour, AccommodationInventoryTour $accommodationInventoryTour)
    {
        return view('pages.models.accommodation_inventory_tours.update', ['tour' => $tour, 'accommodationInventoryTour' => $accommodationInventoryTour,]);
    }

    public function update(Request $request, Tour $tour, AccommodationInventoryTour $accommodationInventoryTour)
    {
        $request->validate(AccommodationInventoryTour::getValidationRules());
        $accommodationInventoryTour->update([
            'accommodation_inventory_id' => $request->input('accommodation_inventory_id'),
            'tour_component_type' => $request->input('tour_component_type'),
            'tour_sales_price' => $request->input('tour_sales_price'),
        ]);
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function destroy(Tour $tour, AccommodationInventoryTour $accommodationInventoryTour)
    {
        $accommodationInventoryTour->delete();
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }
}
