<?php

namespace App\Http\Controllers\Admin\Activity;

use App\Http\Controllers\Controller;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\Tour\Tour;
use Illuminate\Http\Request;

class ActivityInventoryTourController extends Controller
{
    public function create(Tour $tour)
    {
        return view('pages.admin.activity.inventory.tour.form', ['tour' => $tour,]);
    }

    public function store(Request $request, Tour $tour)
    {
        $request->validate(ActivityInventoryTour::getValidationRules());
        $activityInventoryTour = ActivityInventoryTour::make([
            'activity_inventory_id' => $request->input('activity_inventory_id'),
            'tour_component_type' => $request->input('tour_component_type'),
            'tour_sales_price' => $request->input('tour_sales_price') ?? 0,
            'stock_control_active' => $request->input('stock_control_active')  == 'on' ? 1 : 0,
            'is_bookable' =>  $request->input('is_bookable')  == 'on' ? 1 : 0,
        ]);
        $tour->activityInventoryTours()->save($activityInventoryTour);
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function edit(Tour $tour, ActivityInventoryTour $inventoryTour)
    {
        return view('pages.admin.activity.inventory.tour.form', ['tour' => $tour, 'inventoryTour' => $inventoryTour,]);
    }

    public function update(Request $request, Tour $tour, ActivityInventoryTour $inventoryTour)
    {
        $request->validate(ActivityInventoryTour::getValidationRules());
        $inventoryTour->update([
            'activity_inventory_id' => $request->input('activity_inventory_id'),
            'tour_component_type' => $request->input('tour_component_type'),
            'tour_sales_price' => $request->input('tour_sales_price') ?? 0,
            'stock_control_active' => $request->input('stock_control_active')  == 'on' ? 1 : 0,
            'is_bookable' =>  $request->input('is_bookable')  == 'on' ? 1 : 0,
        ]);
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function restore(Tour $tour, $inventoryTour)
    {
        $inventoryTour = ActivityInventoryTour::withTrashed()->find($inventoryTour);
        if ($inventoryTour->trashed()) {
            $inventoryTour->restore();
        } else {
            $inventoryTour->is_bookable = true;
            $inventoryTour->save();
        }
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function destroy(Tour $tour, ActivityInventoryTour $inventoryTour)
    {
        $deleted = $inventoryTour->delete();
        if (!$deleted) {
            return back()->withErrors(['msg' => 'Could not successfully delete the component, as it has dependants']);
        }
        return redirect()->route('tours.view', ['tour' => $tour,])->with('success', 'Component Deleted Successfully');
    }
}
