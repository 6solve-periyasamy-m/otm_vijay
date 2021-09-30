<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use Illuminate\Http\Request;

class FlightController extends Controller {

  public function index() {
    return view('pages.models.flights.table', ['flights' => Flight::all(),]);
  }

  public function create() {
    return view('pages.models.flights.create');
  }

  public function store(Request $request) {
    $flight = Flight::create([
      'airline_id' => $request->input('airline_id'),
      'departure_airport_id' => $request->input('departure_airport_id'),
      'arrival_airport_id' => $request->input('arrival_airport_id'),
      'is_domestic' => $request->input('is_domestic'),
      'notes' => $request->input('notes'),
      'available_after' => $request->input('available_after'),
    ]);
    return redirect()->route('flights.view', ['flight' => $flight, ]);
  }

  public function view(Flight $flight) {
    return view('pages.models.flights.view', ['flight' => $flight, ]);
  }

  public function edit(Flight $flight) {
    return view('pages.models.flights.update', ['flight' => $flight, ]);
  }

  public function update(Request $request, Flight $flight) {
    $flight->update([
      'airline_id' => $request->input('airline_id'),
      'departure_airport_id' => $request->input('departure_airport_id'),
      'arrival_airport_id' => $request->input('arrival_airport_id'),
      'is_domestic' => $request->input('is_domestic'),
      'notes' => $request->input('notes'),
      'available_after' => $request->input('available_after'),
    ]);
    return redirect()->route('flights.view', ['flight' => $flight, ]);
  }

  public function destroy(Flight $flight) {
    $flight->delete();
    return redirect()->route('flights.all');
  }
}
