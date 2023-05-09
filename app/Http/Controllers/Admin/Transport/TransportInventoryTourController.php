<?php

namespace App\Http\Controllers\Admin\Transport;

use App\Http\Controllers\Controller;
use App\Models\Tour\Tour;
use App\Models\Transport\TransportInventoryTour;
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
            'tour_sales_price' => $request->input('tour_sales_price') ?? 0,
            'stock_control_active' => $request->input('stock_control_active')  == 'on' ? 1 : 0,
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
            'tour_sales_price' => $request->input('tour_sales_price') ?? 0,
            'stock_control_active' => $request->input('stock_control_active')  == 'on' ? 1 : 0,
        ]);
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function restore(Tour $tour, $transportInventoryTour)
    {
        $inventoryTour = TransportInventoryTour::withTrashed()->find($transportInventoryTour);
        if ($inventoryTour->trashed()) {
            $inventoryTour->restore();
        } else {
            $inventoryTour->is_bookable = true;
            $inventoryTour->save();
        }
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function destroy(Tour $tour, TransportInventoryTour $transportInventoryTour)
    {
        if ($tour->orders()->count() > 0) {
            $transportInventoryTour->is_bookable = false;
            $transportInventoryTour->save();
        } else {
            $transportInventoryTour->delete();
        }
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }
}
