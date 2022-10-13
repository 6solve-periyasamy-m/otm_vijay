<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Tour\Tour;
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
            'tour_sales_price' => $request->input('tour_sales_price') ?? 0,
            'is_template' => $request->input('is_template')  == 'on' ? 1 : 0,
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
            'tour_sales_price' => $request->input('tour_sales_price') ?? 0,
            'is_template' => $request->input('is_template')  == 'on' ? 1 : 0,
        ]);
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function restore(Tour $tour, $accommodationInventoryTour)
    {
        $inventoryTour = AccommodationInventoryTour::withTrashed()->find($accommodationInventoryTour);
        if ($inventoryTour->trashed()) {
            $inventoryTour->restore();
        } else {
            $inventoryTour->is_bookable = true;
            $inventoryTour->save();
        }
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function destroy(Tour $tour, AccommodationInventoryTour $accommodationInventoryTour)
    {
        if ($tour->orders()->count() > 0) {
            $accommodationInventoryTour->is_bookable = false;
            $accommodationInventoryTour->save();
        } else {
            $accommodationInventoryTour->delete();
        }
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }
}
