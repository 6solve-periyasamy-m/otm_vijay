<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\FlightInventory;
use Illuminate\Http\Request;

class FlightInventoryController extends Controller
{

    public function index()
    {
        return view('pages.models.flight_inventories.table', ['flightInventories' => FlightInventory::all(),]);
    }

    public function create(Flight $flight)
    {
        return view('pages.models.flight_inventories.create', ['flight' => $flight,]);
    }

    public function store(Request $request, Flight $flight)
    {
        $request->validate(FlightInventory::getValidationRules());
        $flightInventory = FlightInventory::make([
            'travel_class_id' => $request->input('travel_class_id'),
            'flight_number' => $request->input('flight_number'),
            'check_in' => $request->input('check_in'),
            'departs_at' => $request->input('departs_at'),
            'arrives_at' => $request->input('arrives_at'),
            'fit_selectable' => $request->input('fit_selectable') === 'on' ? 1 : 0,
            'stock' => $request->input('stock'),
            'purchase_price' => $request->input('purchase_price'),
            'sales_price' => $request->input('sales_price'),
            'notes' => $request->input('notes'),
        ]);
        $flight->flightInventory()->save($flightInventory);
        return redirect()->route('flights.view', ['flight' => $flight, 'flightInventory' => $flightInventory,]);
    }

    public function view(Flight $flight, FlightInventory $flightInventory)
    {
        return view('pages.models.flight_inventories.view', ['flight' => $flight, 'flightInventory' => $flightInventory,]);
    }

    public function edit(Flight $flight, FlightInventory $flightInventory)
    {
        return view('pages.models.flight_inventories.update', ['flight' => $flight, 'flightInventory' => $flightInventory,]);
    }

    public function update(Request $request, Flight $flight, FlightInventory $flightInventory)
    {
        $request->validate(FlightInventory::getValidationRules());
        $flightInventory->update([
            'travel_class_id' => $request->input('travel_class_id'),
            'flight_number' => $request->input('flight_number'),
            'check_in' => $request->input('check_in'),
            'departs_at' => $request->input('departs_at'),
            'arrives_at' => $request->input('arrives_at'),
            'fit_selectable' => $request->input('fit_selectable') === 'on' ? 1 : 0,
            'stock' => $request->input('stock'),
            'purchase_price' => $request->input('purchase_price'),
            'sales_price' => $request->input('sales_price'),
            'notes' => $request->input('notes'),
        ]);
        return redirect()->route('flights.view', ['flight' => $flight,]);
    }

    public function destroy(Flight $flight, FlightInventory $flightInventory)
    {
        if ($flightInventory->flightInventoryTour()->count() > 0) {
            return back()->withErrors(trans('custom.used-in-tour', ['model' => 'Flight Inventory']));
        }
        $flightInventory->delete();
        return redirect()->route('flights.view', ['flight' => $flight,]);
    }

    public function duplicate(Flight $flight, FlightInventory $flightInventory)
    {
        $inventory = $flightInventory->replicate();
        $inventory->save();
        return redirect()->route('flight-inventories.edit', ['flight' => $flight, 'flightInventory' => $inventory,]);
    }
}
