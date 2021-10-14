<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\TransportInventoryTour;
use Illuminate\Http\Request;

class TransportInventoryTourController extends Controller
{

    public function index()
    {
        return view('pages.models.transport_inventory_tours.table', ['transportInventoryTours' => TransportInventoryTour::all(),]);
    }

    public function create()
    {
        return view('pages.models.transport_inventory_tours.create');
    }

    public function store(Request $request)
    {
        $transportInventoryTour = TransportInventoryTour::create([
            'tour_id' => $request->input('tour_id'),
            'transport_inventory_id' => $request->input('transport_inventory_id'),
            'tour_component_type' => $request->input('tour_component_type'),
            'tour_sales_price' => $request->input('tour_sales_price'),
        ]);
        return redirect()->route('transport-inventory-tours.view', ['transportInventoryTour' => $transportInventoryTour,]);
    }

    public function view(TransportInventoryTour $transportInventoryTour)
    {
        return view('pages.models.transport_inventory_tours.view', ['transportInventoryTour' => $transportInventoryTour,]);
    }

    public function edit(TransportInventoryTour $transportInventoryTour)
    {
        return view('pages.models.transport_inventory_tours.update', ['transportInventoryTour' => $transportInventoryTour,]);
    }

    public function update(Request $request, TransportInventoryTour $transportInventoryTour)
    {
        $transportInventoryTour->update([
            'tour_id' => $request->input('tour_id'),
            'transport_inventory_id' => $request->input('transport_inventory_id'),
            'tour_component_type' => $request->input('tour_component_type'),
            'tour_sales_price' => $request->input('tour_sales_price'),
        ]);
        return redirect()->route('transport-inventory-tours.view', ['transportInventoryTour' => $transportInventoryTour,]);
    }

    public function destroy(TransportInventoryTour $transportInventoryTour)
    {
        $transportInventoryTour->delete();
        return redirect()->route('transport-inventory-tours.all');
    }
}
