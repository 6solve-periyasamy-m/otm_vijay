<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Airport;
use Illuminate\Http\Request;

class AirportController extends Controller {

  public function index() {
    return view('pages.models.airports.table', ['airports' => Airport::all(),]);
  }

  public function create() {
    return view('pages.models.airports.create');
  }

  public function store(Request $request) {
    $airport = Airport::create([
      'name' => $request->input('name'),
      'iata_code' => $request->input('iata_code'),
    ]);
    return redirect()->route('airports.view', ['airport' => $airport, ]);
  }

  public function view(Airport $airport) {
    return view('pages.models.airports.view', ['airport' => $airport, ]);
  }

  public function edit(Airport $airport) {
    return view('pages.models.airports.update', ['airport' => $airport, ]);
  }

  public function update(Request $request, Airport $airport) {
    $airport->update([
      'name' => $request->input('name'),
      'iata_code' => $request->input('iata_code'),
    ]);
    return redirect()->route('airports.view', ['airport' => $airport, ]);
  }

  public function destroy(Airport $airport) {
    $airport->delete();
    return redirect()->route('airports.all');
  }
}
