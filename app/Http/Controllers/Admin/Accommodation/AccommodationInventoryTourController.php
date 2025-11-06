<?php

namespace App\Http\Controllers\Admin\Accommodation;

use App\Http\Controllers\Controller;
use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Tour\Tour;
use Illuminate\Http\Request;

class AccommodationInventoryTourController extends Controller
{
    public function create(Tour $tour)
    {
        return view('pages.admin.accommodation.inventory.tour.form', ['tour' => $tour,]);
    }

    public function store(Request $request, Tour $tour)
    {
        $request->validate(AccommodationInventoryTour::getValidationRules());
        $accommodationInventoryTour = AccommodationInventoryTour::make([
            'accommodation_inventory_id' => $request->input('accommodation_inventory_id'),
            'tour_component_type' => $request->input('tour_component_type'),
            'tour_sales_price' => $request->input('tour_sales_price') ?? 0,
            'is_template' => $request->input('is_template')  == 'on' ? 1 : 0,
            'stock_control_active' => $request->input('stock_control_active')  == 'on' ? 1 : 0,
            'is_bookable' =>  $request->input('is_bookable')  == 'on' ? 1 : 0,
            'document_order' => $request->order,
        ]);
        $tour->accommodationInventoryTours()->save($accommodationInventoryTour);
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function edit(Tour $tour, AccommodationInventoryTour $inventoryTour)
    {
        return view('pages.admin.accommodation.inventory.tour.form', ['tour' => $tour, 'inventoryTour' => $inventoryTour,]);
    }

    public function update(Request $request, Tour $tour, AccommodationInventoryTour $inventoryTour)
    {
        $request->validate(AccommodationInventoryTour::getValidationRules());
        $inventoryTour->update([
            'accommodation_inventory_id' => $request->input('accommodation_inventory_id'),
            'tour_component_type' => $request->input('tour_component_type'),
            'tour_sales_price' => $request->input('tour_sales_price') ?? 0,
            'is_template' => $request->input('is_template')  == 'on' ? 1 : 0,
            'stock_control_active' => $request->input('stock_control_active')  == 'on' ? 1 : 0,
            'is_bookable' =>  $request->input('is_bookable')  == 'on' ? 1 : 0,
            'document_order' => $request->order,
        ]);
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function restore(Tour $tour, $inventoryTour)
    {
        $inventoryTour = AccommodationInventoryTour::withTrashed()->find($inventoryTour);
        if ($inventoryTour->trashed()) {
            $inventoryTour->restore();
        } else {
            $inventoryTour->is_bookable = true;
            $inventoryTour->save();
        }
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function destroy(Tour $tour, AccommodationInventoryTour $inventoryTour)
    {
        $deleted = $inventoryTour->delete();
        if (!$deleted) {
            return back()->withErrors(['msg' => 'Could not successfully delete the component, as it has dependants']);
        }
        return redirect()->route('tours.view', ['tour' => $tour,])->with('success', 'Component Deleted Successfully');
    }
}
