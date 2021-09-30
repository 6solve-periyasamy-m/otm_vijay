<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\FlightInventory;
use Illuminate\Http\Request;

class FlightInventoryController extends Controller {

  public function index() {
    return view('pages.models.flight_inventories.table', ['flightInventories' => FlightInventory::all(),]);
  }

  public function create() {
    return view('pages.models.flight_inventories.create');
  }

  public function store(Request $request) {
    $flightInventory = FlightInventory::create([
      'flight_id' => $request->input('flight_id'),
      'travel_class_id' => $request->input('travel_class_id'),
      'flight_number' => $request->input('flight_number'),
      'check_in_date_time' => $request->input('check_in_date_time'),
      'departure_date_time' => $request->input('departure_date_time'),
      'arrival_date_time' => $request->input('arrival_date_time'),
      'fit_selectable' => $request->input('fit_selectable'),
      'stock' => $request->input('stock'),
      'purchase_price' => $request->input('purchase_price'),
      'sales_price' => $request->input('sales_price'),
      'currency' => $request->input('currency'),
      'notes' => $request->input('notes'),
    ]);
    return redirect()->route('flight_inventories.view', ['flightInventory' => $flightInventory, ]);
  }

  public function view(FlightInventory $flightInventory) {
    return view('pages.models.flight_inventories.view', ['flightInventory' => $flightInventory, ]);
  }

  public function edit(FlightInventory $flightInventory) {
    return view('pages.models.flight_inventories.update', ['flightInventory' => $flightInventory, ]);
  }

  public function update(Request $request, FlightInventory $flightInventory) {
    $flightInventory->update([
      'flight_id' => $request->input('flight_id'),
      'travel_class_id' => $request->input('travel_class_id'),
      'flight_number' => $request->input('flight_number'),
      'check_in_date_time' => $request->input('check_in_date_time'),
      'departure_date_time' => $request->input('departure_date_time'),
      'arrival_date_time' => $request->input('arrival_date_time'),
      'fit_selectable' => $request->input('fit_selectable'),
      'stock' => $request->input('stock'),
      'purchase_price' => $request->input('purchase_price'),
      'sales_price' => $request->input('sales_price'),
      'currency' => $request->input('currency'),
      'notes' => $request->input('notes'),
    ]);
    return redirect()->route('flight_inventories.view', ['flightInventory' => $flightInventory, ]);
  }

  public function destroy(FlightInventory $flightInventory) {
    $flightInventory->delete();
    return redirect()->route('flight_inventories.all');
  }
}
