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
        return view('pages.models.flight_inventories.create', ['flight' => $flight, ]);
    }

    public function store(Request $request, Flight $flight)
    {
        $request->validate(FlightInventory::RULES);
        $flightInventory = FlightInventory::make([
            'travel_class_id' => $request->input('travel_class_id'),
            'flight_number' => $request->input('flight_number'),
            'check_in_date_time' => $request->input('check_in_date_time'),
            'departure_date_time' => $request->input('departure_date_time'),
            'arrival_date_time' => $request->input('arrival_date_time'),
            'fit_selectable' => $request->input('fit_selectable') === 'on' ? 1 : 0,
            'stock' => $request->input('stock'),
            'purchase_price' => $request->input('purchase_price'),
            'sales_price' => $request->input('sales_price'),
            'currency' => $request->input('currency'),
            'notes' => $request->input('notes'),
        ]);
        $flight->flightInventory()->save($flightInventory);
        return redirect()->route('flights.view', ['flight' => $flight, 'flightInventory' => $flightInventory,]);
    }

    public function view(FlightInventory $flightInventory, Flight $flight)
    {
        return view('pages.models.flight_inventories.view', ['flight' => $flight, 'flightInventory' => $flightInventory,]);
    }

    public function edit(FlightInventory $flightInventory, Flight $flight)
    {
        return view('pages.models.flight_inventories.update', ['flight' => $flight, 'flightInventory' => $flightInventory,]);
    }

    public function update(Request $request, Flight $flight, FlightInventory $flightInventory)
    {
        $request->validate(FlightInventory::RULES);
        $flightInventory->update([
            'travel_class_id' => $request->input('travel_class_id'),
            'flight_number' => $request->input('flight_number'),
            'check_in_date_time' => $request->input('check_in_date_time'),
            'departure_date_time' => $request->input('departure_date_time'),
            'arrival_date_time' => $request->input('arrival_date_time'),
            'fit_selectable' => $request->input('fit_selectable') === 'on' ? 1 : 0,
            'stock' => $request->input('stock'),
            'purchase_price' => $request->input('purchase_price'),
            'sales_price' => $request->input('sales_price'),
            'currency' => $request->input('currency'),
            'notes' => $request->input('notes'),
        ]);
        return redirect()->route('flights.view', ['flight' => $flight, ]);
    }

    public function destroy(Flight $flight, FlightInventory $flightInventory)
    {
        $flightInventory->delete();
        return redirect()->route('flights.view', ['flight' => $flight, ]);
    }
}
