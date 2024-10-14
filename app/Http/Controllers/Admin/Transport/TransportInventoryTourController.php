<?php

namespace App\Http\Controllers\Admin\Transport;

use App\Http\Controllers\Controller;
use App\Models\Tour\Tour;
use App\Models\Transport\TransportInventoryTour;
use Illuminate\Http\Request;

class TransportInventoryTourController extends Controller
{
    public function create(Tour $tour)
    {
        return view('pages.admin.transport.inventory.tour.form', ['tour' => $tour,]);
    }

    public function store(Request $request, Tour $tour)
    {
        $request->validate(TransportInventoryTour::getValidationRules());
        $transportInventoryTour = TransportInventoryTour::make([
            'transport_inventory_id' => $request->input('transport_inventory_id'),
            'tour_component_type' => $request->input('tour_component_type'),
            'tour_sales_price' => $request->input('tour_sales_price') ?? 0,
            'stock_control_active' => $request->input('stock_control_active')  == 'on' ? 1 : 0,
        ]);
        $tour->transportInventoryTours()->save($transportInventoryTour);
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function edit(Tour $tour, TransportInventoryTour $inventoryTour)
    {
        return view('pages.admin.transport.inventory.tour.form', ['tour' => $tour, 'inventoryTour' => $inventoryTour,]);
    }

    public function update(Request $request, Tour $tour, TransportInventoryTour $inventoryTour)
    {
        $request->validate(TransportInventoryTour::getValidationRules());
        $inventoryTour->update([
            'transport_inventory_id' => $request->input('transport_inventory_id'),
            'tour_component_type' => $request->input('tour_component_type'),
            'tour_sales_price' => $request->input('tour_sales_price') ?? 0,
            'stock_control_active' => $request->input('stock_control_active')  == 'on' ? 1 : 0,
        ]);
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function restore(Tour $tour, $inventoryTour)
    {
        $inventoryTour = TransportInventoryTour::withTrashed()->find($inventoryTour);
        if ($inventoryTour->trashed()) {
            $inventoryTour->restore();
        } else {
            $inventoryTour->is_bookable = true;
            $inventoryTour->save();
        }
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function destroy(Tour $tour, TransportInventoryTour $inventoryTour)
    {
        $deleted = $inventoryTour->delete();
        if (!$deleted) {
            return back()->withErrors(['msg' => 'Could not successfully delete the component, as it has dependants']);
        }
        return redirect()->route('tours.view', ['tour' => $tour,])->with('success', 'Component Deleted Successfully');
    }
}
