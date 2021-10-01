<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\FlightInventoryTour;
use Illuminate\Http\Request;

class FlightInventoryTourController extends Controller {

  public function index() {
    return view('pages.models.flight_inventory_tours.table', ['flightInventoryTours' => FlightInventoryTour::all(),]);
  }

  public function create() {
    return view('pages.models.flight_inventory_tours.create');
  }

  public function store(Request $request) {
    $flightInventoryTour = FlightInventoryTour::create([
      'tour_id' => $request->input('tour_id'),
      'flight_inventory_id' => $request->input('flight_inventory_id'),
      'tour_component_type' => $request->input('tour_component_type'),
      'flight_type' => $request->input('flight_type'),
      'tour_sales_price' => $request->input('tour_sales_price'),
    ]);
    return redirect()->route('flight-inventory-tours.view', ['flightInventoryTour' => $flightInventoryTour, ]);
  }

  public function view(FlightInventoryTour $flightInventoryTour) {
    return view('pages.models.flight_inventory_tours.view', ['flightInventoryTour' => $flightInventoryTour, ]);
  }

  public function edit(FlightInventoryTour $flightInventoryTour) {
    return view('pages.models.flight_inventory_tours.update', ['flightInventoryTour' => $flightInventoryTour, ]);
  }

  public function update(Request $request, FlightInventoryTour $flightInventoryTour) {
    $flightInventoryTour->update([
      'tour_id' => $request->input('tour_id'),
      'flight_inventory_id' => $request->input('flight_inventory_id'),
      'tour_component_type' => $request->input('tour_component_type'),
      'flight_type' => $request->input('flight_type'),
      'tour_sales_price' => $request->input('tour_sales_price'),
    ]);
    return redirect()->route('flight-inventory-tours.view', ['flightInventoryTour' => $flightInventoryTour, ]);
  }

  public function destroy(FlightInventoryTour $flightInventoryTour) {
    $flightInventoryTour->delete();
    return redirect()->route('flight-inventory-tours.all');
  }
}
